<?php

class ErgebnisController {

private $dispatcher, $mandant_id;

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $dispatcher) {

    $this->dispatcher = $dispatcher;
    $this->mandant_id = $dispatcher->getMandantId();

    switch($action) {
        case "bilanz":
            return $this->getBilanz();
        case "guv":
            return $this->getGuV();
        case "guv_month":
            return $this->getGuVMonth($request);
        case "verlauf":
            return $this->getVerlauf($request);
        case "verlauf_gewinn":
            return $this->getVerlaufGewinn();
        case "months":
            return $this->getMonths();
        default:
            $message = array();
            $message['message'] = "Unbekannte Action";
            return $message;
    }
}

# Berechnet eine aktuelle Bilanz und liefert
# sie als Array zurück
function getBilanz() {
    $result = array();
    $db = getDbConnection();
    $rs = mysqli_query($db, "select konto, kontenname, saldo from fi_ergebnisrechnungen where mandant_id = $this->mandant_id and kontenart_id in (1, 2)");
    $zeilen = array();
    while($erg = mysqli_fetch_object($rs)) {
        $zeilen[] = $erg;
    }
    $result['zeilen'] = $zeilen;
    $rs = mysqli_query($db, "select kontenart_id, sum(saldo) saldo from fi_ergebnisrechnungen
        where kontenart_id in (1, 2) and mandant_id = $this->mandant_id
        group by kontenart_id
        union 
        select '5', sum(saldo) saldo from fi_ergebnisrechnungen 
        where kontenart_id in (1, 2) and mandant_id = $this->mandant_id");
    $ergebnisse = array();
    while($erg = mysqli_fetch_object($rs)) {
        $ergebnisse[] = $erg;
    }
    $result['ergebnisse'] = $ergebnisse;
    mysqli_close($db); 
    return $result;
}

# Berechnet eine aktuelle GuV-Rechnung und liefert
# sie als Array zurück
function getGuV() {
    $db = getDbConnection();
    $rs = mysqli_query($db, "select konto, kontenname, saldo from fi_ergebnisrechnungen "
          ." where mandant_id = $this->mandant_id and kontenart_id in (3, 4)");
    $zeilen = array();
    $result = array();
    while($erg = mysqli_fetch_object($rs)) {
        $zeilen[] = $erg;
    }
    $result['zeilen'] = $zeilen;
    $rs = mysqli_query($db, "select kontenart_id, sum(saldo) saldo from fi_ergebnisrechnungen
        where kontenart_id in (3, 4) and mandant_id = $this->mandant_id
        group by kontenart_id
        union 
        select '5', sum(saldo) saldo from fi_ergebnisrechnungen 
        where kontenart_id in (3, 4) and mandant_id = $this->mandant_id");
    $ergebnisse = array();
    while($erg = mysqli_fetch_object($rs)) {
        $ergebnisse[] = $erg;
    }
    $result['ergebnisse'] = $ergebnisse;
    mysqli_close($db);
    return $result;
}

# Berechnet eine GuV-Rechnung fuer das angegebene oder aktuelle Monat
# und liefert sie als Array zurück
function getGuVMonth($request) {
    $month_id = $this->getMonthFromRequest($request);

    $db = getDbConnection();
    $rs = mysqli_query($db, "select konto, kontenname, sum(betrag) as saldo from fi_ergebnisrechnungen_base ".
          " where mandant_id = $this->mandant_id and (year(datum)*100)+month(datum) = ".$month_id." and kontenart_id in (3, 4) ".
          "group by konto, kontenname");
    $zeilen = array();
    $result = array();
    while($erg = mysqli_fetch_object($rs)) {
        $zeilen[] = $erg;
    }
    $result['zeilen'] = $zeilen;
    $rs = mysqli_query($db, "select kontenart_id, sum(betrag) saldo from fi_ergebnisrechnungen_base
        where mandant_id = $this->mandant_id and kontenart_id in (3, 4) and gegenkontenart_id not in (5) 
          and (year(datum)*100)+month(datum) = ".$month_id."
        group by kontenart_id
        union 
        select '5', sum(betrag) saldo from fi_ergebnisrechnungen_base 
        where mandant_id = $this->mandant_id and kontenart_id in (3, 4) and gegenkontenart_id not in (5) 
          and (year(datum)*100)+month(datum) = ".$month_id);
    $ergebnisse = array();
    while($erg = mysqli_fetch_object($rs)) {
        $ergebnisse[] = $erg;
    }
    $result['ergebnisse'] = $ergebnisse;
    mysqli_close($db);
    return $result;
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
    $sql .= " order by yearmonth";

    $rs = mysqli_query($db, $sql);
    while($obj = mysqli_fetch_object($rs)) {
        $months[] = $obj->yearmonth;
    }

    mysqli_free_result($rs);
    mysqli_close($db);
    return $months;
}

# Verlauf Aufwand, Ertrag, Aktiva und Passiva in Monatsraster
function getVerlauf($request) {
    $db = getDbConnection();
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

        mysqli_close($db);
    } 
    return $result;
}

# Verlauf des Gewinns in Monatsraster
function getVerlaufGewinn() {
    $db = getDbConnection();
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

    mysqli_close($db);
    
    return $result;
}


}

?>
