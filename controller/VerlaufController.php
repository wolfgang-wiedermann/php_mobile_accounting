<?php

class VerlaufController {

private $dispatcher, $mandant_id;

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $dispatcher) {
    $this->dispatcher = $dispatcher;
    $this->mandant_id = $dispatcher->getMandantId();
    switch($action) {
        case "monatssalden":
            return $this->getMonatsSalden($request['id']);
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

# Ermittelt die Monats-Salden des Kontos
function getMonatsSalden($kontonummer) {
error_log("Kontonummer : ".$kontonummer."---");
    if(is_numeric($kontonummer) || $this->is_numeric_list($kontonummer)) {
        $kto_prepared = $this->prepareKontoNummern($kontonummer);
error_log("Kontonummern vorverarbeitet : ".$kto_prepared."---");
        $db = getDbConnection();
        $rechnungsart = $this->getRechnungsart($kto_prepared, $db);
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

error_log($sql);
 
                $rs = mysqli_query($db, $sql);
            } else if($rechnungsart == 1) {
                // Laufende Summen, fuer Bestandskonten
                $rs = mysqli_query($db, "select x1.grouping, sum(x2.betrag) as saldo "
                      ."from (select distinct (year(datum)*100)+month(datum) as grouping from fi_buchungen_view "
                      ."where mandant_id = '$this->mandant_id') x1 "
                      ."inner join (select (year(datum)*100+month(datum)) as grouping, konto, betrag "
                      ."from fi_buchungen_view where mandant_id = '$this->mandant_id') x2 "
                      ."on x2.grouping <= x1.grouping "
                      ."where konto = '$kontonummer' and x1.grouping > ((year(now())*100)+month(now()))-100 "
                      ."group by grouping, konto");
            }
            $result = array();
            while($obj = mysqli_fetch_object($rs)) {
                $result[] = $obj;
            }
            mysqli_free_result($rs);
            mysqli_close($db);
            return $result;
        } else {
            mysqli_close($db);
            throw new Exception("Kontonummer unbekannt");
        }
    } else throw new Exception("Kontonummer nicht numerisch");
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
   // TODO: Da ist noch was zu tun ;-)
   return true;
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
