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

    function setParameterUnchecked($paramName, $paramValue) {
        $this->sql = str_replace("#".$paramName."#", $paramValue, $this->sql);
    }

    function getSql() {
        return $this->sql;
    }
}

?>
