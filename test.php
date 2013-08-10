<?php

$_REQUEST = array();
# Test 1
#$_REQUEST['controller'] = 'konto';
#$_REQUEST['action'] = 'list';
# Test 2
$_REQUEST['controller'] = 'buchung';
$_REQUEST['action'] = 'listbykonto';
$_REQUEST['konto'] = '2400';
include("./index.php");

?>
