<?php

class KontoController {

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $user) {
    switch($action) {
        case "get":
            return $this->getKonto($request['id']);
        case "list":
            return $this->getKonten();
        case "save":
            return $this->saveKonto($request);
        case "create":
            return $this->createKonto($request);
        case "saldo":
            return $this->getSaldo($request['id']);
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

# Liest eines einzelnes Konto aus und liefert
# sie als Objekt zurück
function getKonto($id) {
    if(is_numeric($id)) {
        $db = getDbConnection();
        $rs = mysqli_query($db, "select * from fi_konto where kontonummer = $id");
        $erg = mysqli_fetch_object($rs);
        mysqli_close($db); 
        return $erg;
    } else throw Exception("Kontonummer nicht numerisch");
}

function getSaldo($id) {
    if(is_numeric($id)) {
        $db = getDbConnection();
        $rs = mysqli_query($db, "select saldo from fi_ergebnisrechnungen where konto = '$id'");
        $erg = mysqli_fetch_object($rs);
        mysqli_close($db);
        return $erg->saldo;
    } else throw Exception("Kontonummer nicht numerisch");
}

# Erstellt eine Liste aller Kontenarten
function getKonten() {
    $db = getDbConnection();
    $result = array();
    $rs = mysqli_query($db, "select * from fi_konto order by kontenart_id, kontonummer");
    while($obj = mysqli_fetch_object($rs)) {
        $result[] = $obj;
    }
    mysqli_close($db);
    return $result;
}

# Speichert das als JSON-Objekt übergebene Konto
function saveKonto($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE ); 
    $sql = "update fi_konto set bezeichnung = '".$input['bezeichnung']."', kontenart_id = ".$input['kontenart_id']
          ." where kontonummer = ".$input['kontonummer'];
    mysqli_query($db, $sql);
    mysqli_close($db);
    return $void = array();
}

# legt das als JSON-Objekt übergebene Konto an
function createKonto($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    $sql = "insert into fi_konto values ('".$input['kontonummer']."', '".$input['bezeichnung']
          ."', ".$input['kontenart_id'].")";
    mysqli_query($db, $sql);
    mysqli_close($db);
    return $void = array();
}

}

?>
