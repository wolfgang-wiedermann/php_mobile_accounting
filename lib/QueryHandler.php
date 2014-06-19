<?php

/*
* Klasse zur Verwendung von SQL-Abfragen die in eigenständigen Dateien 
* gespeichert werden.
*/
class QueryHandler {

    private $path;
    private $sql;

    function __construct($path) {
        $this->path = $path;
        $this->loadSql();
    }

    function loadSql() {
        $this->sql = file_get_contents("./sql/query/".$this->path);
    }

    function setParameter($paramName, $paramValue) {
        // TODO: Strings von $paramName und $paramValue prüfen
        $this->sql = str_replace("#".$paramName."#", $paramValue, $this->sql);
    }

    function getSql() {
        return $this->sql;
    }
}

?>
