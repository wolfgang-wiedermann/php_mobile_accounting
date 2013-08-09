<?php

class Dispatcher {

# Attribute
private $request, $user;

# Einstiegspunkt in den Dispatcher
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

# Benutzer übergeben
function setRemoteUser($user) {
    $this->user = $user;
    logX("Remote use ".$this->user." registered");
}

# Ein Objekt der angefragten Controller-Klasse laden
function getControllerObject() {
    $name = $this->getControllerString();
    // TODO: Prüfen, ob $name eine ungültigen Zeichen enthält
    $fileName = "./controller/".ucwords($name)."Controller.php";
    logX("Controller-Datei: ".$fileName);
    require_once($fileName);
    $className = ucwords($name)."Controller";
    $obj = new $className;
    return $obj;
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
