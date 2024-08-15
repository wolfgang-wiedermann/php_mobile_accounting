<?php

/*
* From Oct. 2021 on, the usage of getDbConnection is deprecated because of
* switching to PDO to make html5-haushaltsbuch compatible to mysql and postgresql in
* future. (Currently still only mysql and mariadb supported)
*/
function getDbConnection() {
    trigger_error("Deprecated function called.", E_USER_NOTICE);
    $db = mysqli_connect("db", "root", "example");
    mysqli_select_db($db, "fibu");
    return $db;
}

function getPdoConnection() {
    $pdo = new PDO('mysql:host=db;dbname=fibu', 'root', 'example');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

?>
