<?php

class MenuController {
function invoke($action, $request, $user) {
    switch($action) {
        case 'quick':
    	     return $this->getQuickMenu($request);
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
}

?>
