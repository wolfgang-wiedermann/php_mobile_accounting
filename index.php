<?php
# HTTP-Header standardmäßig auf application/json stellen
header("Content-type: application/json");
# Logger und Datenbank-Helper laden
require_once("./lib/Logger.php");
require_once("./lib/Database.php");
# So umstellen das Errors als Exceptions geliefert werden
include_once("./lib/ErrorsToExceptions.php");
# Einstiegspunkt in das Framework
require_once("./lib/Dispatcher.php");
$disp = new Dispatcher();
try {
    $disp->setRemoteUser($_SERVER['REMOTE_USER']);
} catch(ErrorException $ex) {
    $disp->setRemoteUser(null);
}
echo $disp->invoke($_REQUEST);

?>
