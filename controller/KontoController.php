<?php

class KontoController {

private $dispatcher, $mandant;

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $dispatcher) {
    $this->dispatcher = $dispatcher;
    $this->mandant = $dispatcher->getMandantId();
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
        $rs = mysqli_query($db, "select * from fi_konto where kontonummer = $id and mandant_id = $this->mandant");
        $erg = mysqli_fetch_object($rs);
        mysqli_close($db); 
        return $erg;
    } else throw Exception("Kontonummer nicht numerisch");
}

function getSaldo($id) {
    if(is_numeric($id)) {
        $db = getDbConnection();
        // TODO: in fi_ergebnisrechnungen gibts noch keinen Mandanten!
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
    $rs = mysqli_query($db, "select * from fi_konto where mandant_id = $this->mandant order by kontenart_id, kontonummer");
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
          ." where kontonummer = ".$input['kontonummer']." and mandant_id = ".$this->mandant;
    mysqli_query($db, $sql);
    mysqli_close($db);
    return $void = array();
}

# legt das als JSON-Objekt übergebene Konto an
function createKonto($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    $sql = "insert into fi_konto (kontonummer, bezeichnung, kontenart_id, mandant_id) values ('"
          .$input['kontonummer']."', '".$input['bezeichnung']
          ."', ".$input['kontenart_id'].", ".$this->mandant.")";
    mysqli_query($db, $sql);
    mysqli_close($db);
    return $void = array();
}

}

?>
