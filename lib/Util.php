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

# 
# Verpacken des Response von Controller-Methoden
#
function wrap_response($obj="", $format="json") {

    #error_log("Objekt ".json_encode($obj));
    #error_log("Format ".$format);

    $response = new Response();
    $response->obj = $obj;
    $response->format = $format;

    return $response;
}

class Response {
    public $obj;
    public $format;
}

#
# Ermittelt einen Konfigurations-Key incl. aller seiner Parameter
#
function get_config_key($param_knz, $mandant_id) {
    $db = getDbConnection();
    if(is_legal_string($param_knz)) {
        $sql = "select * from fi_config_params where mandant_id = $mandant_id and param_knz = '$param_knz'";
        $rs = mysqli_query($db, $sql);
        if($obj = mysqli_fetch_object($rs)) {
            mysqli_close($db);
            return $obj;
        } else {
            mysqli_close($db);
            throw new ErrorException("Parameter nicht gefunden");
        }
    } else {
        throw new ErrorException("Param_knz enthält ungültige Zeichen");
    }
}

#
# Prüft, ob ein String Hochkommas enthält
#
function is_legal_string($value) {
    $pattern = '/[\']/';
    preg_match($pattern, $value, $results);
    return count($results) == 0;
}

?>
