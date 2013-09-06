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


}

?>
