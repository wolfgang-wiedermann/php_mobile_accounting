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

    function getSql() {
        return $this->sql;
    }
}

?>
