<?php
require_once("../lib/Databases.php");

/*
* Installationsroutinen für die bequeme Installation der Buchhaltung
*/
function create_database($db_name, $user, $password) {
   $sql = file_get_contents("../sql/create-tables-and-views.sql");
   $db = getDbConnection();
   mysqli_query($db, $sql);
   mysqli_close($db);
}

/*
* Installationsschritte bündeln
*/
function install() {
   create_database($REQUEST['db_name'], $REQUEST['db_user'], $REQUEST['db_password']);
   // TODO: Danach noch einen Mandanten und 
}

// und danach gemeinsam ausführen
if($REQUEST['db_name'] != null) {
   // Wenn die Parameter ausgefüllt sind, dann Installation starten
   install();
} else {
   // Sonst Maske zur eingabe der Parameter (db_name, user, password) anzeigen
   show_frontend();
}
?>
