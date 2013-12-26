<?php

$_REQUEST = array();
$_SERVER = array();
# Basis
$_SERVER['REMOTE_USER'] = 'test';
# Test 1
$_REQUEST['controller'] = 'konto';
$_REQUEST['action'] = 'list';
# Test 2
#$_REQUEST['controller'] = 'buchung';
#$_REQUEST['action'] = 'listbykonto';
#$_REQUEST['konto'] = '2800';
# Test 3
#$_REQUEST['controller'] = 'ergebnis';
#$_REQUEST['action'] = 'guv';
include("./index.php");

?>
