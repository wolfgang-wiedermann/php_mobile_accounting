<?php

# Controller für die Schnellbuchungs-Menüeinträge
class MenuController {
function invoke($action, $request, $dispatcher) {
    switch($action) {
        case 'quick':
    	     return $this->getQuickMenu();
        case 'get':
             return $this->getQuickMenuById($request);
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

function getQuickMenu() {
    $db = getDbConnection();
    $lst = array();
    $rs = mysqli_query($db, "select * from fi_quick_config order by config_knz");
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
        $rs = mysqli_query($db, "select * from fi_quick_config where config_id = $id");
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
}
?>
