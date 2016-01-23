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

class ErgebnisController {

private $dispatcher, $mandant_id;

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $dispatcher) {

    $this->dispatcher = $dispatcher;
    $this->mandant_id = $dispatcher->getMandantId();

    switch($action) {
        case "bilanz":
            return $this->getBilanz($request);
        case "guv":
            return $this->getGuV($request);
        case "guv_month":
            return $this->getGuVMonth($request);
        case "guv_prognose":
            return $this->getGuVPrognose();
        case "verlauf":
            return $this->getVerlauf($request);
        case "verlauf_gewinn":
            return $this->getVerlaufGewinn();
        case "months":
            return $this->getMonths();
        case "years":
            return $this->getYears();
        default:
            $message = array();
            $message['message'] = "Unbekannte Action";
            return $message;
    }
}

# Berechnet eine aktuelle Bilanz und liefert
# sie als Array zurück
function getBilanz($request) {
    $result = array();
    $db = getDbConnection();
    $year = $request['year'];

    if($this->isValidYear($year)) {
        $query = new QueryHandler("bilanz_detail.sql");
        $query->setParameterUnchecked("mandant_id", $this->mandant_id);
        $query->setNumericParameter("year", $year+1);
        $query->setNumericParameter("geschj_start_monat",
            get_config_key("geschj_start_monat", $this->mandant_id)->param_value);
        $sql = $query->getSql();

        $rs = mysqli_query($db, $sql);

        $zeilen = array();
        while ($erg = mysqli_fetch_object($rs)) {
            $zeilen[] = $erg;
        }
        $result['zeilen'] = $zeilen;

        $query = new QueryHandler("bilanz_summen.sql");
        $query->setParameterUnchecked("mandant_id", $this->mandant_id);
        $query->setNumericParameter("year", $year+1);
        $query->setNumericParameter("geschj_start_monat",
            get_config_key("geschj_start_monat", $this->mandant_id)->param_value);
        $sql = $query->getSql();
        $rs = mysqli_query($db, $sql);

        $ergebnisse = array();
        while ($erg = mysqli_fetch_object($rs)) {
            $ergebnisse[] = $erg;
        }
        $result['ergebnisse'] = $ergebnisse;
        mysqli_close($db);
        return wrap_response($result);
    } else {
        return wrap_response("Fehler aufgetreten, das angegebene Jahr hat ein ungültiges Format");
    }
}

# Berechnet eine aktuelle GuV-Rechnung und liefert
# sie als Array zurück
function getGuV($request) {
    $db = getDbConnection();
    $year = $request['year'];
    if($this->isValidYear($year)) {

        $query = new QueryHandler("guv_jahr.sql");
        $query->setParameterUnchecked("mandant_id", $this->mandant_id);
        $query->setParameterUnchecked("jahr_id", $year);
        $query->setParameterUnchecked("geschj_start_monat",
            get_config_key("geschj_start_monat", $this->mandant_id)->param_value);
        $sql = $query->getSql();
   
        $rs = mysqli_query($db, $sql);
        $zeilen = array();
        $result = array();
        while($erg = mysqli_fetch_object($rs)) {
            $zeilen[] = $erg;
        }
        $result['zeilen'] = $zeilen;

        $query = new QueryHandler("guv_jahr_summen.sql");
        $query->setParameterUnchecked("mandant_id", $this->mandant_id);
        $query->setParameterUnchecked("jahr_id", $year);
        $query->setParameterUnchecked("geschj_start_monat",
            get_config_key("geschj_start_monat", $this->mandant_id)->param_value);
        $sql2  = $query->getSql();

        $rs = mysqli_query($db, $sql2);
        $ergebnisse = array();
        while($erg = mysqli_fetch_object($rs)) {
            $ergebnisse[] = $erg;
        }
        $result['ergebnisse'] = $ergebnisse;
        mysqli_close($db);
        return wrap_response($result);
    } else {
        return wrap_response("Der übergebene Parameter year erfüllt nicht die Formatvorgaben für gültige Jahre");
    }
}

# Berechnet eine GuV-Rechnung fuer das angegebene oder aktuelle Monat
# und liefert sie als Array zurück
function getGuVMonth($request) {
    $month_id = $this->getMonthFromRequest($request);

    $db = getDbConnection();
    $query = new QueryHandler("guv_monat.sql");
    $query->setParameterUnchecked("mandant_id", $this->mandant_id);
    $query->setParameterUnchecked("monat_id", $month_id);
    $sql = $query->getSql();

    $rs = mysqli_query($db, $sql);
    $zeilen = array();
    $result = array();
    while($erg = mysqli_fetch_object($rs)) {
        $zeilen[] = $erg;
    }
    $result['zeilen'] = $zeilen;

    $query = new QueryHandler("guv_monat_summen.sql");
    $query->setParameterUnchecked("mandant_id", $this->mandant_id);
    $query->setParameterUnchecked("monat_id", $month_id);
    $sql = $query->getSql();

    $rs = mysqli_query($db, $sql);
    $ergebnisse = array();
    while($erg = mysqli_fetch_object($rs)) {
        $ergebnisse[] = $erg;
    }
    $result['ergebnisse'] = $ergebnisse;

    mysqli_close($db);
    return wrap_response($result);
}

#
# Laden der GuV-Prognose
# (GuV aktuelles-Monat + Vormonat)
function getGuVPrognose() {
    $db = getDbConnection();

    $query = new QueryHandler("guv_prognose.sql");
    $query->setParameterUnchecked("mandant_id", $this->mandant_id);
    $sql = $query->getSql();

    $rs = mysqli_query($db, $sql);

    $result = array();
    $result['detail'] = array();
    while($erg = mysqli_fetch_object($rs)) {
        $result['detail'][] = $erg;
    }

    mysqli_free_result($rs);

    $query = new QueryHandler("guv_prognose_summen.sql");
    $query->setParameterUnchecked("mandant_id", $this->mandant_id);
    $sql = $query->getSql();

    $rs = mysqli_query($db, $sql);
    
    $result['summen'] = array();
    while($erg = mysqli_fetch_object($rs)) {
        $result['summen'][] = $erg;
    }

    mysqli_close($db);
    return wrap_response($result);
}

# Ermittelt aus dem Request und dessen Parameter "id" das ausgewählte Monat
# sofern das möglich ist. Ansonsten wird 'Undef' zurückgegeben
function getMonthFromRequest($request) {
    // Monat aus dem Request auslesen und dann ggf. verwenden (ansonsten das jetzt verwenden)
    $month_id = 'Undef';
    if(array_key_exists('id', $request)) {
        $month_id = $request['id'];
    }
    if(!is_numeric($month_id)) {
        $month_id = date('Ym');
    }
    return $month_id;
}

# Liefert eine Liste der gültigen Monate aus den Buchungen des Mandanten
function getMonths() {
    $db = getDbConnection();
    $months = array();

    $sql =  "select distinct (year(datum)*100)+month(datum) as yearmonth ";
    $sql .= " from fi_buchungen where mandant_id = ".$this->mandant_id;
    $sql .= " order by yearmonth desc";

    $rs = mysqli_query($db, $sql);
    while($obj = mysqli_fetch_object($rs)) {
        $months[] = $obj->yearmonth;
    }

    mysqli_free_result($rs);
    mysqli_close($db);
    return wrap_response($months);
}

# Liefert eine Liste der gültigen Jahre aus den Buchungen des Mandanten
function getYears() {
    $db = getDbConnection();
    $years = array();

    $sql = "select distinct year(date_add(datum, INTERVAL 13-";
    $sql .= get_config_key("geschj_start_monat", $this->mandant_id)->param_value." MONTH))-1 as year ";
    $sql .= "from fi_buchungen where mandant_id = ".$this->mandant_id;
    $sql .= " order by year desc";

    $rs = mysqli_query($db, $sql);
    while($obj = mysqli_fetch_object($rs)) {
        $years[] = $obj->year;
    }

    mysqli_free_result($rs);
    mysqli_close($db);
    return wrap_response($years);
}

# Verlauf Aufwand, Ertrag, Aktiva und Passiva in Monatsraster
function getVerlauf($request) {
    $result = array();

    if(!array_key_exists('id', $request)) 
        return $result;

    $kontenart_id = $request['id'];
    if(is_numeric($kontenart_id)) {

        $db = getDbConnection();

        if($kontenart_id == 4 || $kontenart_id == 1)
            $sql =  "select (year(datum)*100)+month(datum) as grouping, sum(betrag)*-1 as saldo ";
        else
            $sql =  "select (year(datum)*100)+month(datum) as grouping, sum(betrag) as saldo ";
        $sql .= "from fi_ergebnisrechnungen_base ";
        $sql .= "where kontenart_id = $kontenart_id and gegenkontenart_id <> 5 and mandant_id = $this->mandant_id ";

        # Nur immer die letzten 12 Monate anzeigen
        $sql .= "and (year(datum)*100)+month(datum) >= ((year(now())*100)+month(now()))-100 ";

        $sql .= "group by kontenart_id, year(datum), month(datum) ";
        $sql .= "order by grouping";

        $rs = mysqli_query($db, $sql);
        while($erg = mysqli_fetch_object($rs)) {
            $result[] = $erg;
        }

        mysqli_free_result($rs);
        mysqli_close($db);
    } 
    return wrap_response($result);
}

# Verlauf des Gewinns in Monatsraster
function getVerlaufGewinn() {
    $result = array();
    $db = getDbConnection();

    $sql =  "select (year(datum)*100)+month(datum) as grouping, sum(betrag*-1) as saldo ";
    $sql .= "from fi_ergebnisrechnungen_base ";
    $sql .= "where kontenart_id in (3, 4) and gegenkontenart_id <> 5 and mandant_id = $this->mandant_id ";

    # Nur immer die letzten 12 Monate anzeigen
    $sql .= "and (year(datum)*100)+month(datum) >= ((year(now())*100)+month(now()))-100 ";

    $sql .= "group by year(datum), month(datum) ";
    $sql .= "order by grouping";

    $rs = mysqli_query($db, $sql);
    while($erg = mysqli_fetch_object($rs)) {
        $result[] = $erg;
    }

    mysqli_free_result($rs);
    mysqli_close($db);
    
    return wrap_response($result);
}

# Prüft, ob das Zahlenformat des übergebenen Jahres korrekt ist
function isValidYear($year) {
    // Jahr-Regex: [0-9]{4}
    if(preg_match("/[0-9]{4}/", $year, $matches) == 1) {
        if($matches[0] == $year) {
            return true;
        }
    } 
    return false;
}
}

?>
