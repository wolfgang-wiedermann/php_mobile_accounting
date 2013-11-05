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
            return $this->getGuVMonth();
        case "verlauf":
            return $this->getVerlauf($request);
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

# Berechnet eine aktuelle Bilanz und liefert
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

# sie als Array zurück
function getGuVMonth() {
    $db = getDbConnection();
    $rs = mysqli_query($db, "select konto, kontenname, sum(betrag) as saldo from fi_ergebnisrechnungen_base ".
          " where mandant_id = $this->mandant_id and month(datum) = month(now()) and kontenart_id in (3, 4) group by konto, kontenname");
    $zeilen = array();
    $result = array();
    while($erg = mysqli_fetch_object($rs)) {
        $zeilen[] = $erg;
    }
    $result['zeilen'] = $zeilen;
    $rs = mysqli_query($db, "select kontenart_id, sum(betrag) saldo from fi_ergebnisrechnungen_base
        where mandant_id = $this->mandant_id and kontenart_id in (3, 4) and month(datum) = month(now())
        group by kontenart_id
        union 
        select '5', sum(betrag) saldo from fi_ergebnisrechnungen_base 
        where mandant_id = $this->mandant_id and kontenart_id in (3, 4) and month(datum) = month(now())");
    $ergebnisse = array();
    while($erg = mysqli_fetch_object($rs)) {
        $ergebnisse[] = $erg;
    }
    $result['ergebnisse'] = $ergebnisse;
    mysqli_close($db);
    return $result;
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
        $sql .= "where kontenart_id = $kontenart_id ";
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


}

?>
