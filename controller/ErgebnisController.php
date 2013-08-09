<?php

class ErgebnisController {

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $user) {
    switch($action) {
        case "bilanz":
            return $this->getBilanz();
        case "guv":
            return $this->getGuV();
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
    $rs = mysqli_query($db, "select konto, kontenname, saldo from fi_ergebnisrechnungen where kontenart_id in (1, 2)");
    $zeilen = array();
    while($erg = mysqli_fetch_object($rs)) {
        $zeilen[] = $erg;
    }
    $result['zeilen'] = $zeilen;
    $rs = mysqli_query($db, "select kontenart_id, sum(saldo) saldo from fi_ergebnisrechnungen
        where kontenart_id in (1, 2)
        group by kontenart_id
        union 
        select '5', sum(saldo) saldo from fi_ergebnisrechnungen 
        where kontenart_id in (1, 2)");
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
    $rs = mysqli_query($db, "select konto, kontenname, saldo from fi_ergebnisrechnungen where kontenart_id in (3, 4)");
    $zeilen = array();
    while($erg = mysqli_fetch_object($rs)) {
        $zeilen[] = $erg;
    }
    mysqli_close($db);
    return $zeilen;
}


}

?>
