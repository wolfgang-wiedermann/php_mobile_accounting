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
	case "checksystem":
            return $this->checkSystem();
        case "checkdbsettings":
            return $this->checkDatabaseSettings($request);
        case "storedbsettings":
            return $this->storeDatabaseSettings($request);
        case "createdbschema":
            return $this->createDatabaseSchema();
        case "adduser":
            return $this->addUser($request);
        case "sethtaccess":
            return $this->setHtAccess();
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

# Überprüft das System auf bekannte Installationsprobleme
function checkSystem() {
    $result = array();

    # Prüfen ob die Datenbankverbindung bereits konfiguriert ist, bzw. 
    # nach der Konfiguration gespeichert werden kann.
    $db_config_path = $this->getAppRootDir()."lib/Database.php";
    if(file_exists($db_config_path)) {
        $result[] = "Die Datenbank-Konfigurationsdatei $db_config_path existiert bereits";
        if(is_writeable($db_config_path)) {
            $result[] = "Die Datenbank-Konfiguration kann überschrieben werden";
        } else {
            $result[] = "Die Datenbank-Konfigurationsdatei ist schreibgeschützt";
        }
    } else {
        $db_config_folder = $this->getAppRootDir()."lib/";
        if(is_writeable($db_config_folder)) {
            $result[] = "Die Datenbank-Konfiguration kann durch dieses Installationsprogramm im Ordner $db_config_folder angelegt werden";
        } else {
            $result[] = "Bitte heben Sie den Schreibschutz auf den Ordner $db_config_folder für die Dauer der Installation auf";
        }
    }

    # Prüfen ob die .htaccess-Datei bereits konfiguriert ist
    $htaccess_path = $this->getAppRootDir().".htaccess";
    if(file_exists($htaccess_path)) {
        $result[] = "Die .htaccess-Datei existiert bereits unter $htaccess_path";
    }

    $apache_conf_path = "/etc/apache2/apache2.conf";
    if(file_exists($apache_conf_path) && is_readable($apache_conf_path)) {
    	$apache_conf = file_get_contents($apache_conf_path);
	if(strpos($apache_conf, "#AccessFileName .htaccess") !== false) {
            $result[] = "Bitte entfernen Sie # am Anfang der Zeile #AccessFileName .htaccess in $apache_conf_path";
        } else {
            $result[] = "Stellen Sie sicher, dass in $apache_conf_path im Directory-Eintrag zu / AllowOverride All steht";
        }
    } else {
	$result[] = "Stellen Sie sicher, dass Ihre Apache-Installation .htaccess-Dateien auswertet";
    }

    return wrap_response($result);
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

    return wrap_response("Verbindung erfolgreich hergestellt, Test erfolgreich");
}

# Speichert die Datenbankeinstellungen in die Datei lib/Database.php
# Wenn ein Speichern nicht möglich ist wird eine Fehlermeldung ausgegeben
function storeDatabaseSettings($request) {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE);

    // Prüfen der Datenbankeinstellungen: Führt im Fehlerfall zu einer Exception
    $this->checkDatabaseSettings($request);
   
    // Konfigurationsdatei laden 
    $path = $this->getAppRootDir()."lib/Database.php";
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
        error_log("Gespeichert!");
        $response['isError'] = FALSE;
        $response['message'] = "Erfolgreich als $path gespeichert.\nAktuelles Verzeichnis:".getcwd();
        return wrap_response($response);

    } catch(Exception $ex) {
        if(file_exists($path)) {
          $response['isError'] = FALSE;
          $response['message'] = "Die Datei $path existiert bereits und wurde nicht überschrieben";
        } else {
          $response['isError'] = TRUE;
          $response['message'] = "Die Datei $path konnte nicht geschrieben werden, keine Schreibrechte vorhanden!";
          $response['content'] = $content;
        }
        return wrap_response($response);
    } 
}

# Anlegen des Datenbankschemas
# unter Verwendung von sql/create-tables-and-views.sql
function createDatabaseSchema() {
    $sql = file_get_contents("../sql/create-tables-and-views.sql");
    require_once("../lib/Database.php");

    $sql_statements = explode(";", $sql);

    $dbo = getDboConnection();
    foreach($sql_statements as $sql) {
      #error_log($sql);
      $num = $pdo->exec($sql);

      if($num === false) {
        $result = array();
        $result['isError'] = TRUE;
        $result['message'] = "Datenbankfehler aufgetreten: $error";
        $result['sql'] = $sql;
        return wrap_response($result);
      }
 
   }

   $result = array();
   $result['isError'] = FALSE;
   $result['message'] = "Schema erfolgreich angelegt.";
   return wrap_response($result);
    
}

# Fügt einen Benutzer für das Haushaltsbuch hinzu
# (Alle Benutzer werden dem automatisch angelegten Mandanten 1 zugeordnet!)
function addUser($request) {
    $inputJSON = file_get_contents("php://input");
    $input = json_decode($inputJSON, TRUE);

    if(!$this->isValidBenutzerObject($input)) {
       throw new Exception("Fehler: Das übergebene Benutzerobjekt ist fehlerhaft");
    }

    $appRootDir = $this->getAppRootDir();

    // evtl. bestehende .htpasswd auslesen (um ggf. neuen Benutzer anzufügen)
    $htpasswd = $this->getExistingHtpasswd($appRootDir);
    $htpasswd .= $this->createHtpasswdEntry($input['username'], $input['password']);

    error_log("HTPASSWD: ".$htpasswd);

    // Benutzer in fi_users eintragen
    $this->addUserToDb($input['username']);

    // Prüfen, ob schreibrechte im ROOT-Verzeichnis des Haushaltsbuchs vorliegen
    if(is_writeable($appRootDir) || is_writeable($appRootDir.".htpasswd")) {
       file_put_contents($appRootDir.".htpasswd", $htpasswd);
       
       $message = array();
       $message['isError'] = FALSE;
       $message['message'] = "Der Benutzer wurde erfolgreich in die Datei .htpasswd im Ordner $appRootDir geschrieben "
                            ."und in der Datenbank dem Mandanten 1 zugeordnet";
      
       return wrap_response($message); 
    } else {
       // 2. Wenn nein, inhalt der .htpasswd bei Weiter anzeigen 
       //    (mit Hinweis wohin die Datei soll)
       $message = array();
       $message['isError'] = TRUE;
       $message['message'] = "Der Benutzer konnte der Datei .htpasswd nicht hinzugefügt werden. Bitte legen Sie diese "
                            ."manuell im Ordner $appRootDir an. Die Zuordnung des Benutzers zu Mandant 1 in der "
                            ."Datenbank wurde ausgeführt.";
       $message['htpasswd'] = $htpasswd;
       return wrap_response($message);
    }
}

# .htaccess-Datei erstellen und auf .htpasswd verweisen
function setHtAccess() {
   $appRootDir = $this->getAppRootDir();
   $htaccess = file_get_contents($appRootDir."htaccess.template");
   $htaccess = str_replace("%PATH_TO_HTPASSWD%", $appRootDir.".htpasswd", $htaccess);

   if(is_writable($appRootDir) 
      || (file_exists($appRootDir.".htaccess") && is_writable($appRootDir.".htaccess"))) {
      try {
         file_put_contents($appRootDir.".htaccess", $htaccess);
      } catch(ErrorException $ex) {
         $message = array();
         $message['isError'] = TRUE;
         $message['message'] = "Die .htaccess-Datei konnte nicht nach $appRootDir geschrieben werden.";
         $message['htaccess'] = $htaccess;
         return wrap_response($message);
      }

      $message = array();
      $message['isError'] = FALSE;
      $message['message'] = "Der Passwortschutz Ihrer Anwendung wurde aktiviert";
      return wrap_response($message);
   } else {
      $message = array();
      $message['isError'] = TRUE;
      $message['message'] = "Die .htaccess-Datei konnte nicht nach $appRootDir geschrieben werden.";
      $message['htaccess'] = $htaccess;
      return wrap_response($message);
   }
}

# Installation abschließen
function finishInstallation() {

}

# Ermittelt das Stammverzeichnis der Installation des HTML5-Haushaltsbuchs
private function getAppRootDir() {
    return substr(getcwd(), 0, strlen(getcwd())-7);
}

# Prüft, ob ein übergebenes Benutzerobjekt das korrekte Format hat.
private function isValidBenutzerObject($input) {
   if(!(array_key_exists('username', $input) 
        && array_key_exists('password', $input))) {
      return false;
   }
   $pattern = '/[\']/';

   preg_match($pattern, $input['username'], $results);
   $username_ok = count($results) == 0;

   preg_match($pattern, $input['password'], $results);
   $password_ok = count($results) == 0;

   return $username_ok && $password_ok;
}

# Speichern eines Nutzers in der Datenbank
# Mit automatischer Zuordnung zu Mandant 1
private function addUserToDb($username) {
    require_once("../lib/Database.php");
    $pdo = getPdoConnection();

    $sql = "insert into fi_user values(0, '$username', 'Benutzer: $username', 1, now())";
    $pdo->exec($sql);
}

# Auslesen einer mglw. bestehenden .htpasswd-Datei
private function getExistingHtpasswd($appRootDir) {
    if(file_exists($appRootDir.".htpasswd")) {
       $htpasswd = file_get_contents($appRootDir.".htpasswd");
       return $htpasswd;
    } else {
       return "";
    }
}

# Baut den String für einen htpasswd-Benutzereintrag zusammen
# und gibt diesen als Rückgabewert zurück
private function createHtpasswdEntry($username, $passwd) {
    return "".$username.":".$this->htpasswd($passwd)."\n";
}

# Interne Hilfsfunktion
# Verschlüsselt ein gegebenes Passwort passend für eine
# .htpasswd-Datei
private function htpasswd($passwd) {
  // Alter Ansatz mit Unix-Crypt -> unsafe  
  //return crypt($passwd, base64_encode($passwd));
  return "{SHA}".base64_encode(sha1($passwd, true));
}

}

?>
