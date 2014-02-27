<?php

# Die Klasse Dispatcher kapselt die Funktionalitaet,
# zur Weiterleitung der Anfragen an die passenden
# Controller-Klassen.
class Dispatcher {
# Attribute
private $request, $user, $user_id, $mandant;

# Einstiegspunkt in den Dispatcher
# (wird automatisch aus ../index.php aufgerufen)
function invoke($request) {
    $this->request = $request;
    if($this->isValidControllerName()) {
        $controller = $this->getControllerObject();
        $action = $this->getActionName();
        if(array_key_exists ("outputtype" , $request) 
           && $request["outputtype"] == 'csv'
           && $request["controller"] == 'ergebnis') {
	    return $this->csvEncode($controller->invoke($action, $this->request, $this));
        } else {
            return json_encode($controller->invoke($action, $this->request, $this));
        }
    } else {
          return "{'message':'ControllerName enthält ungültige Zeichen'}";
    }    
}

# Ermitteln der Mandant-Nummer des aktuell angemeldeten Benutzers
# (Zur Nutzung im jeweiligen Controller)
function getMandantId() {
    return $this->mandant;
}

# Ermittlung des Benutzernamens des aktuell angemeldeten Benutzers
# (Zur Nutzung im jeweiligen Controller)
function getUser() {
    return $this->user;
}

# Ermittlung der Benutzer-ID des aktuell angemeldeten Benutzers
# (Zur Nutzung im jeweiligen Controller)
function getUserId() {
    return $this-> user_id;
}

# Methode zum Encodieren von Arrays in *.csv-Strings
function csvEncode($data) {

    #error_log(print_r($data));

    $csv = ""; $header = ""; $id = 0;
    foreach($data['zeilen'] as $line) {
        $count = 2; # count($line);
        $i = 0;
        foreach($line as $key => $value) {
            if($id == 0) {
                 $header .= $key;
                 if($i < $count) {
                     $header .= ";";
                 }
            }
            $csv .= $value;
            if($i < $count) {
                $csv .= ";";
            }
            $i++;
        }    
        $csv .= "\n";
        $id++;
    }
    return $header."\n".$csv; 
}

# Methode zum uebergeben des Benutzers an den Dispatcher
# ermittelt den zugeordneten Mandanten und setzt dessen id in das Feld $this->mandant_id
function setRemoteUser($user) {
    $db = getDbConnection();
    $this->user = $user;
    $rs = mysqli_query($db, "select mandant_id, user_id from fi_user where user_name = '$user'");
    if($rs && $obj = mysqli_fetch_object($rs)) {
        $this->mandant = $obj->mandant_id;
        $this->user_id = $obj->user_id;
    } else {
        throw new Exception("Kein Mandant für den Benutzer $user konfiguriert");
    }
    mysqli_close($db);
    logX("Remote use ".$this->user." registered");
}

# Ein Objekt der angefragten Controller-Klasse laden
function getControllerObject() {
    $name = $this->getControllerString();
    if($this->isValidControllerName()) {
        $fileName = "./controller/".ucwords($name)."Controller.php";
        logX("Controller-Datei: ".$fileName);
        require_once($fileName);
        $className = ucwords($name)."Controller";
        $obj = new $className;
        return $obj;
    } else {
        throw new ErrorException("Controller-Name ungueltig");
    }
}

# Den angefragten Controller ermitteln
function getControllerString() {
    return $this->request['controller'];
} 

# Prüft, ob der ControllerName keine ungültigen Zeichen enthält
function isValidControllerName() {
    # Regex: [^a-zA-Z0-9]
    $pattern = '/[^a-zA-Z0-9]/';
    preg_match($pattern, $this->getControllerString(), $results);
    return count($results) == 0;
}

# Die angefragte Action ermitteln
function getActionName() {
    return $this->request['action'];
}

}

?>
