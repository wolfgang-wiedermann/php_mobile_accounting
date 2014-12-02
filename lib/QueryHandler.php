<?php
/*
 * Copyright (c) 2014 by Wolfgang Wiedermann
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

    function setStringParameter($paramName, $paramValue) {
        if($this->isValidString($paramValue)) {
            $this->setParameterUnchecked($paramName, $paramValue);
        } else {
            throw new Exception("Der übergebene Wert entspricht nicht den Anforderungen "
                ."an einen String-Parameter");
        }
    }

    function setNumericParameter($paramName, $paramValue) {
        if($this->isValidNumber($paramValue)) {
            $this->setParameterUnchecked($paramName, $paramValue);
        } else {
            throw new Exception("Der übergebene Wert entspricht nicht den Anforderungen "
                ."an einen numerischen Parameter");
        }
    }

    function isValidString($string) {
        $pattern = "/[']/";
        preg_match($pattern, $string, $results);
        return count($results) == 0;
    }

    function isValidNumber($number) {
        $pattern = "/[^0-9\\.,]/";
        preg_match($pattern, $number, $results);
        return count($results) == 0;
    }

    function getSql() {
        return $this->sql;
    }
}

?>
