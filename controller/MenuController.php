<?php

class MenuController {
function invoke($action, $request, $user) {
    switch($action) {
        case 'quick':
    	     return $this->getQuickMenu($request);
        case 'get':
             return $this->getQuickMenuById($request);
        default:
            return array();
    }
}

function getQuickMenu($request) {
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
    $rs = mysqli_query($db, "select * from fi_quick_config where config_id = $id");
    if($obj = mysqli_fetch_object($rs)) {
        mysqli_close($db);
        return $obj;
    } else {
        mysqli_close($db);
        return null;
    }
}
}
?>
