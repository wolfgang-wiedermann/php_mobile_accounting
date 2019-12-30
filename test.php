<?php

include_once("lib/Database.php");

# Datenbankkonfig testen:
$db = getDbConnection();
#print("ERROR?: " . mysqli_error($db));
$rs = mysqli_query($db, "select * from information_schema.tables");
#print("ERROR?: " . mysqli_error($db));
mysqli_close($db);


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
#$_REQUEST['outputtype'] = 'csv';
# Test 4
#$_REQUEST['controller'] = 'office';
#$_REQUEST['action'] = 'journal';
#$_REQUEST['format'] = 'json';

include("./index.php");

?>
