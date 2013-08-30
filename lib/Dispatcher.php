<?php

# Die Klasse Dispatcher kapselt die Funktionalitaet,
# zur Weiterleitung der Anfragen an die passenden
# Controller-Klassen.
class Dispatcher {

# Attribute
private $request, $user;

# Einstiegspunkt in den Dispatcher
# (wird automatisch aus ../index.php aufgerufen)
function invoke($request) {
    $this->request = $request;
    if($this->isValidControllerName()) {
        $controller = $this->getControllerObject();
        $action = $this->getActionName();
        return json_encode($controller->invoke($action, $this->request, $this->user));
    } else {
          return "{'message':'ControllerName enthält ungültige Zeichen'}";
    }    
}

# Methode zum uebergeben des Benutzers an den Dispatcher
function setRemoteUser($user) {
    $this->user = $user;
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
