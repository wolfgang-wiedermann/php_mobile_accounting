<?php
/*
 * Copyright (c) 2013 by Wolfgang Wiedermann
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; version 3 of the
 * License.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 * USA
 */

class InstallationController {

private $dispatcher;

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $dispatcher) {
    $this->dispatcher = $dispatcher;
    switch($action) {
        case "checkdbsettings":
            return $this->checkDatabaseSettings($request);
        case "storedbsettings":
            return $this->storeDatabaseSettings($request);
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

# Liest eines einzelnes Konto aus und liefert
# sie als Objekt zurück
# TODO: Die Texte im Fehlerfall stimmen noch nicht: im 2. Fall ist 1. nicht sicher ausgeschlossen!
function checkDatabaseSettings($request) {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE); 

    #ggf. Debug-Ausgaben
    #error_log("Eingehende Daten: ".$inputJSON);
    #error_log("Analysierte Daten: ".print_r($input, TRUE));

    $db = mysqli_connect($input['hostname'], $input['username'], $input['password']); 
    $error = mysqli_error($db);

    if($error != null) {
        throw new ErrorException("Verbindung konnte nicht hergestellt werden. Hostname, Benutzername oder Passwort sind falsch");
    }

    mysqli_select_db($db, $input['database']);
    mysqli_query($db, "show tables");

    $error = mysqli_error($db);

    if($error != null) {
        throw new ErrorException("Die Verbindung konnte hergestellt werden, die gewählte Datenbank existiert aber nicht");
    }

    mysqli_close($db);

    return "Verbindung erfolgreich hergestellt, Test erfolgreich";
}

# Speichert die Datenbankeinstellungen in die Datei lib/Database.php
# Wenn ein Speichern nicht möglich ist wird eine Fehlermeldung ausgegeben
function storeDatabaseSettings($request) {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE);

    // Prüfen der Datenbankeinstellungen: Führt im Fehlerfall zu einer Exception
    $this->checkDatabaseSettings($request);
   
    // Konfigurationsdatei laden 
    $path = "../lib/Database.php";
    $content = file_get_contents($path.".template");

    // Felder ersetzen
    $content = str_replace('hostname', $input['hostname'], $content);
    $content = str_replace('username', $input['username'], $content);
    $content = str_replace('password', $input['password'], $content);
    $content = str_replace('databasename', $input['database'], $content);

    // Konfigurationsdatei speichern
    file_put_contents($path, $content);

    return "Erfolgreich als $path gespeichert";
}

}

?>
