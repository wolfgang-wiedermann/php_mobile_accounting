<?php

class KontenartController {

private $dispatcher;

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $dispatcher) {
    $this->dispatcher = $dispatcher;
    switch($action) {
        case "get":
            return $this->getKontenart($request['id']);
        case "list":
            return $this->getKontenarten();
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

# Liest eines einzelne Kontenart aus und liefert
# sie als Objekt zurück
function getKontenart($id) {
    if(is_numeric($id)) {
        $db = getDbConnection();
        $rs = mysqli_query($db, "select * from fi_kontenart where kontenart_id = $id");
        $erg = mysqli_fetch_object($rs);
        mysqli_close($db);
        return $erg;
    } else {
        throw new ErrorException("Eine nicht numerische Kontenart-ID ist ungültig");
    }
}

# Erstellt eine Liste aller Kontenarten
function getKontenarten() {
    $db = getDbConnection();
    $result = array();
    $rs = mysqli_query($db, "select * from fi_kontenart");
    while($obj = mysqli_fetch_object($rs)) {
        $result[] = $obj;
    }
    mysqli_close($db);
    return $result;
}

}

?>
