<?php
/*
 * Copyright (c) 2013 by Wolfgang Wiedermann
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; version 3 of the
 * License.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 * USA
 */

class VerlaufController {

private $dispatcher, $mandant_id;

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $dispatcher) {
    $this->dispatcher = $dispatcher;
    $this->mandant_id = $dispatcher->getMandantId();
    switch($action) {
        case "monatssalden":
            return $this->getMonatsSalden($request['id']);
        case "cashflow":
            return $this->getCashFlow($request['id'], $request['side']);
        case "intramonth":
            return $this->getIntraMonth($request);
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

# Ermittelt die Monats-Salden des Kontos
function getMonatsSalden($kontonummer) {
    if(is_numeric($kontonummer) || $this->is_numeric_list($kontonummer)) {
        $kto_prepared = $this->prepareKontoNummern($kontonummer);
        $db = getDbConnection();
        $rechnungsart = $this->getRechnungsart($kto_prepared);
        if($rechnungsart != 0) {
           if($rechnungsart == 2) {
                // Monatssummen, fuer Aufwands- und Ertragskonten
                $sql = "select grouping, sum(saldo)*-1 as saldo from "
                      ."(select grouping, konto, sum(betrag) as saldo from "
                      ."(select (year(v.datum)*100)+month(v.datum) as grouping, v.konto, v.betrag "
                      ."from fi_ergebnisrechnungen_base v inner join fi_konto kt "
                      ."on v.konto = kt.kontonummer and v.mandant_id = kt.mandant_id "
                      ."where v.mandant_id = $this->mandant_id "
                      ."and v.gegenkontenart_id <> 5) as x "
                      ."group by grouping, konto) as y "
                      ."where y.konto in ($kto_prepared) " 
                      ."and y.grouping > ((year(now())*100)+month(now()))-100 "
                      ."group by grouping ";

                $rs = mysqli_query($db, $sql);
            } else if($rechnungsart == 1) {
                // Laufende Summen, fuer Bestandskonten
                $sql = "select x1.grouping, sum(x2.betrag) as saldo "
                      ."from (select distinct (year(datum)*100)+month(datum) as grouping from fi_buchungen_view "
                      ."where mandant_id = '$this->mandant_id') x1 "
                      ."inner join (select (year(datum)*100+month(datum)) as grouping, konto, betrag "
                      ."from fi_buchungen_view where mandant_id = '$this->mandant_id') x2 "
                      ."on x2.grouping <= x1.grouping "
                      ."where konto in ($kto_prepared) and x1.grouping > ((year(now())*100)+month(now()))-100 "
                      ."group by grouping";

                $rs = mysqli_query($db, $sql);
            }
            $result = array();
            while($obj = mysqli_fetch_object($rs)) {
                $result[] = $obj;
            }
            mysqli_free_result($rs);
            mysqli_close($db);
            return wrap_response($result);
        } else {
            mysqli_close($db);
            throw new Exception("Mindestens eine Kontonummer ist unbekannt");
        }
    } else throw new Exception("Mindestens eine Kontonummer ist nicht numerisch");
}

# Ermittelt die monatlichen Werte des Zu- oder Abfluss 
# ($side = S => Sollbuchungen)
# ($side = H => Habenbuchungen)
# von Aktivkonten. Bei anderen Kontenarten wird eine
# Exception zurückgeliefert
function getCashFlow($kontonummer, $side) {
    $values = array();
    if($this->isAktivKonto($kontonummer)) {
        $db = getDbConnection();
        
        if($side == 'S') {
            $sql  = "select (year(datum)*100)+month(datum) as grouping, sum(b.betrag) as saldo ";
            $sql .= "from fi_buchungen as b ";
            $sql .= " inner join fi_konto as k ";
            $sql .= " on k.mandant_id = b.mandant_id and k.kontonummer = b.habenkonto ";
            $sql .= " where b.mandant_id = ".$this->mandant_id;
            $sql .= " and b.sollkonto = '".$kontonummer."' ";
            $sql .= " and year(b.datum) >= year(now())-1 ";
            $sql .= " and year(b.datum) <= year(now()) ";
            $sql .= " and k.kontenart_id <> 5 ";
            $sql .= "group by (year(b.datum)*100)+month(b.datum);";
        } else if($side == 'H') {
            $sql  = "select (year(b.datum)*100)+month(b.datum) as grouping, sum(b.betrag) as saldo ";
            $sql .= "from fi_buchungen as b ";
            $sql .= " inner join fi_konto as k ";
            $sql .= " on k.mandant_id = b.mandant_id and k.kontonummer = b.sollkonto ";
            $sql .= " where b.mandant_id = ".$this->mandant_id;
            $sql .= " and b.habenkonto = '".$kontonummer."' ";
            $sql .= " and year(b.datum) >= year(now())-1 ";
            $sql .= " and year(b.datum) <= year(now()) ";
            $sql .= " and k.kontenart_id <> 5 ";
            $sql .= "group by (year(b.datum)*100)+month(b.datum);";
        } else {
            mysqli_close($db);
            throw new Exception("Gültige Werte für side sind S und H");
        }

        $rs = mysqli_query($db, $sql);
        while($obj = mysqli_fetch_object($rs)) {
            $values[] = $obj;
        }
        mysqli_free_result($rs);
        mysqli_close($db);
    } else {
        throw new Exception("getCashFlow ist nur für Aktiv-Konten verfügbar");
    }
    return wrap_response($values);
}

# Monats-internen Verlauf ermitteln
function getIntraMonth($request) {
    $db = getDbConnection();

    if(isset($request['month_id'])) { 
      if($this->is_number($request['month_id'])) {

        $month_id = $request['month_id'];

        $query = new QueryHandler("guv_intramonth_aufwand.sql");
        $query->setParameterUnchecked("mandant_id", $this->mandant_id);
        $query->setParameterUnchecked("month_id", $month_id);
        $sql = $query->getSql();

        $result = array();
        $rs = mysqli_query($db, $sql);
        while($obj = mysqli_fetch_object($rs)) {
            $result[] = $obj;
        }

        mysqli_free_result($rs);
        mysqli_close($db);

        return wrap_response($result);

      } else {
        return wrap_response("Parameter month_id ist nicht ausschließlich numerisch");
      }
    } else {
        return wrap_response("Parameter month_id fehlt");
    }
}

# Prüft, ob das angegebene Konto ein Aktiv-Konto ist.
function isAktivKonto($kontonummer) {
    if(!is_numeric($kontonummer)) return false;
    $db = getDbConnection();
    $rs = mysqli_query($db, "select kontenart_id from fi_konto "
                            ."where mandant_id = ".$this->mandant_id
                            ." and kontonummer = '".$kontonummer."'");
    $isActive = false;
    if($obj = mysqli_fetch_object($rs)) {
        $isActive = $obj->kontenart_id == 1; // Ist Aktiv-Konto
    }
    mysqli_free_result($rs);
    mysqli_close($db);
    return $isActive;
}

# Macht aus einer oder mehreren durch Komma getrennten Kontonummern
# ein Array von Kontonummern-Strings und verwirft dabei 
# nichtnumerische Elemente
function kontonummernToArray($value) {
    $list = array();
    if(is_numeric($value)) {
        $list[] = $value;
    } else {
        $tmp = explode(',', $value);
        foreach($tmp as $item) {
            if(is_numeric($item)) {
                $list[] = $item;
            }
        }
    }
    return $list;
}

# Macht aus einer oder mehreren durch Komma getrennten Kontonummern
# eine passende Liste für SQL-IN
function prepareKontoNummern($value) {
    $list = $this->kontonummernToArray($value);

    $result = "";
    foreach($list as $item) {
        $result .= "'".$item."',";
    }
    $result = substr($result, 0, strlen($result)-1);
    return $result;
}

# Prüft mittels RegEx ob $value ausschließlich aus Ziffern und Kommas besteht
function is_numeric_list($value) {
    $pattern = '/[^0-9,]/';
    preg_match($pattern, $value, $results);
    return count($results) == 0;
}

# Prüft mittels RegEx ob der übergebene Wert ausschließlich aus Ziffern besteht
function is_number($value) {
    $pattern = '/[^0-9]/';
    preg_match($pattern, $value, $results);
    return count($results) == 0;
}

# Ermittelt, ob es sich bei den ausgewählten Konten um 
# eine GUV-Betrachtung (nur Aufwand und Ertrag) oder
# eine Bestandsbetrachtung (nur Aktiv und Passiv) handelt.
function getRechnungsart($kto_prepared) {
    $db = getDbConnection();
    $kontenarten = array();
    $type = 0;
    $sql = "select distinct kontenart_id from fi_konto where kontonummer in ($kto_prepared)";
    $rs = mysqli_query($db, $sql);
    while($obj = mysqli_fetch_object($rs)) {
        $kontenart_id = $obj->kontenart_id;
        if($type == 0) {
            // noch ERGEBNISOFFEN
            if($kontenart_id == 1 || $kontenart_id == 2) $type = 1;
            else if($kontenart_id == 3 || $kontenart_id == 4) $type = 2;
        } else if($type == 1) {
            // BESTANDSBETRACHTUNG
            if($kontenart_id == 3 || $kontenart_id == 4) throw new Exception("Falsche Mischung von Kontenarten");
        } else if($type == 2) {
            // GUV-BETRACHTUNG
            if($kontenart_id == 1 || $kontenart_id == 2) throw new Exception("Falsche Mischung von Kontenarten");
        }
    }
    mysqli_free_result($rs);
    mysqli_close($db);
    return $type;
}

}

?>
