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
        case "createdbschema":
            return $this->createDatabaseSchema();
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
    $path = substr(getcwd(), 0, strlen(getcwd())-7)."lib/Database.php";
    $content = file_get_contents($path.".template");

    // Felder ersetzen
    $content = str_replace('hostname', $input['hostname'], $content);
    $content = str_replace('username', $input['username'], $content);
    $content = str_replace('password', $input['password'], $content);
    $content = str_replace('databasename', $input['database'], $content);

    // Konfigurationsdatei speichern
    $response = array();
    try {
        file_put_contents($path, $content);
        $response['isError'] = FALSE;
        $response['message'] = "Erfolgreich als $path gespeichert.\nAktuelles Verzeichnis:".getcwd();
        return $response;

    } catch(Exception $ex) {
        if(file_exists($path)) {
          $response['isError'] = FALSE;
          $response['message'] = "Die Datei $path existiert bereits und wurde nicht überschrieben";
        } else {
          $response['isError'] = TRUE;
          $response['message'] = "Die Datei $path konnte nicht geschrieben werden, keine Schreibrechte vorhanden!";
          $response['content'] = $content;
        }
        return $response;
    } 
}

# Anlegen des Datenbankschemas
# unter Verwendung von sql/create-tables-and-views.sql
function createDatabaseSchema() {
    $sql = file_get_contents("../sql/create-tables-and-views.sql");
    require_once("../lib/Database.php");

    $sql_statements = explode(";", $sql);

    $db = getDbConnection();
    foreach($sql_statements as $sql) {
      #error_log($sql);
      mysqli_query($db, $sql);

      $error = mysqli_error($db);
      if($error != null && $error != "Query was empty") {
        mysqli_close($db);
        $result = array();
        $result['isError'] = TRUE;
        $result['message'] = "Datenbankfehler aufgetreten: $error";
        $result['sql'] = $sql;
        return $result;
      }
 
   }

   mysqli_close($db);
   $result = array();
   $result['isError'] = FALSE;
   $result['message'] = "Schema erfolgreich angelegt.";
   return $result;
    
}

}

?>
