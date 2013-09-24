<?php

# Controller für die Schnellbuchungs-Menüeinträge
class MenuController {

private $dispatcher, $mandant_id;

function invoke($action, $request, $dispatcher) {
    $this->dispatcher = $dispatcher;
    $this->mandant_id = $dispatcher->getMandantId();
    switch($action) {
        case 'quick':
    	     return $this->getQuickMenu();
        case 'get':
             return $this->getQuickMenuById($request);
        case 'add':
             return $this->addQuickMenu($request);
        case 'remove':
             return $this->removeQuickMenu($request);
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

function getQuickMenu() {
    $db = getDbConnection();
    $lst = array();
    $rs = mysqli_query($db, "select * from fi_quick_config where mandant_id = $this->mandant_id order by config_knz");
    while($obj = mysqli_fetch_object($rs)) {
        $lst[] = $obj;
    }
    mysqli_close($db);
    return $lst;
}


function getQuickMenuById($request) {
    $db = getDbConnection();
    $id = $request['id'];
    if(is_numeric($id)) {
        $rs = mysqli_query($db, "select * from fi_quick_config where mandant_id = $this->mandant_id and config_id = $id");
        if($obj = mysqli_fetch_object($rs)) {
            mysqli_close($db);
            return $obj;
        } else {
            mysqli_close($db);
            return null;
        }
    } else {
        throw new ErrorException("Die fi_quick_config id ist fehlerhaft");
    }
}

function addQuickMenu($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    if($this->isValidQuickMenu($input)) {
        $sql = "insert into fi_quick_config(config_knz, sollkonto, habenkonto, buchungstext,";
        $sql .= " mandant_id) values ('$input->config_knz', '$input->sollkonto', ";
        $sql .= "'$input->habenkonto', '$input->buchungstext', $this->mandant_id)";

        mysqli_query($db, $sql);
        mysqli_close($db);
        return "";
    } else {
        mysqli_close($db);
        throw new ErrorException("Die uebergebene Schnellbuchungsvorlage ist nicht valide");
    }
}

function removeQuickMenu($request) {
    $db = getDbConnection();
    $id = $request['id'];
    if(is_numeric($id)) {
        $sql =  "delete from fi_quick_config where mandant_id = $this->mandant_id";
        $sql .= " and config_id = $id";

        mysqli_query($db, $sql);
        mysqli_close($db);
    } else {
        throw new ErrorException("Die id der Schnellbuchungsvorlage muss numerisch sein!");
    }
}

# Prüft ob $menu ein valides QuickMenu-Objekt ist
# Typen und Felder prüfen
function isValidQuickMenu($menu) {
    if(count($menu) < 4 && count($menu) > 7) {
        return false;
    } 
    foreach($menu as $key => $value) {
        if(!$this->isValidFieldAndValue($key, $value)) return false;
    }
    return true;
}

# Prüft ein einzelnes Feld uns seinen Inhalt auf Gültigkeit
function isValidFieldAndValue($key, $value) {
    switch($key) {
        case 'config_id': 
        case 'sollkonto':
        case 'habenkonto': 
        case 'betrag':
        case 'mandant_id':
            return is_numeric($value);
        case 'buchungstext':
        case 'config_knz':
            $pattern = '/[\']/';
            preg_match($pattern, $value, $results);
            return count($results) == 0;
        default: 
            return false;
    }
}
}
?>
