<?php

class BuchungController {

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $user) {
    switch($action) {
        case "create":
            return $this->createBuchung($request);
        case "aktuellste":
            return $this->getTop25($request);
        case "listbykonto":
            return $this->getListByKonto($request);
        default:
            $message = array();
            $message['message'] = "Unbekannte Action";
            return $message;
    }
}

# legt das als JSON-Objekt übergebene Konto an
function createBuchung($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    $sql = "insert into fi_buchungen (buchungstext, sollkonto, habenkonto, betrag, datum) values ('".$input['buchungstext']
          ."', '".$input['sollkonto']."', '".$input['habenkonto']."', ".$input['betrag'].", '".$input['datum']."')";
    mysqli_query($db, $sql);
    mysqli_close($db);
    return $void = array();
}

# liest die aktuellsten 25 Buchungen aus
function getTop25($request) {
    $db = getDbConnection();
    $top10 = array();
    $rs = mysqli_query($db, "select * from fi_buchungen order by buchungsnummer desc limit 25");
    while($obj = mysqli_fetch_object($rs)) {
        $top10[] = $obj;
    }
    mysqli_close($db);
    return $top10;
}

function getListByKonto($request) {
    $db = getDbConnection();
    $kontonummer = $request['konto'];
    # Nur verarbeiten, wenn konto eine Ziffernfolge ist, um SQL-Injections zu vermeiden
    if(is_numeric($request['konto'])) {
        $rs = mysqli_query($db, "SELECT buchungsnummer, buchungstext, gegenkonto, betrag, datum FROM `fi_buchungen_view` "
                               ."where konto = '$kontonummer'");
        $result = array();
        $result_list = array();
        // Buchungen laden
        while($obj = mysqli_fetch_object($rs)) {
            $result_list[] = $obj;
        }
        $result['list'] = $result_list;
        // Saldo laden
        $rs = mysqli_query($db, "select sum(betrag) as saldo from fi_buchungen_view where konto = '$kontonummer'");
        if($obj = mysqli_fetch_object($rs)) {
            $result['saldo'] = $obj->saldo;
        } else {
            $result['saldo'] = "unbekannt";
        }
        mysqli_close($db);
        return $result;
    # Wenn konto keine Ziffernfolge ist, leeres Ergebnis zurück liefern
    } else {
        $result = array();
        $result['list'] = array();
        $result['saldo'] = 0;
        return $result;
    }
}

}

?>
