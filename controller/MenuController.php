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

# Controller für die Schnellbuchungs-Menüeinträge
class MenuController {

private $dispatcher, $mandant_id;

function invoke($action, $request, $dispatcher) {
    $this->dispatcher = $dispatcher;
    $this->mandant_id = $dispatcher->getMandantId();
    switch($action) {
        case 'quick':
    	     return $this->getQuickMenu();
        case 'get':
             return $this->getQuickMenuById($request);
        case 'add':
             return $this->addQuickMenu($request);
        case 'update':
             return $this->updateQuickMenu($request);
        case 'remove':
             return $this->removeQuickMenu($request);
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

function getQuickMenu() {
    $db = getDbConnection();
    $lst = array();
    $rs = mysqli_query($db, "select * from fi_quick_config where mandant_id = $this->mandant_id order by config_knz");
    while($obj = mysqli_fetch_object($rs)) {
        $lst[] = $obj;
    }
    mysqli_free_result($rs);
    mysqli_close($db);
    return wrap_response($lst);
}


function getQuickMenuById($request) {
    $db = getDbConnection();
    $id = $request['id'];
    if(is_numeric($id)) {
        $rs = mysqli_query($db, "select * from fi_quick_config where mandant_id = $this->mandant_id and config_id = $id");
        if($obj = mysqli_fetch_object($rs)) {
            mysqli_free_result($rs);
            mysqli_close($db);
            return wrap_response($obj);
        } else {
            mysqli_free_result($rs);
            mysqli_close($db);
            return wrap_response(null);
        }
    } else {
        throw new ErrorException("Die fi_quick_config id ist fehlerhaft");
    }
}

function addQuickMenu($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    if($this->isValidQuickMenu($input)) { 
        $sql = "insert into fi_quick_config(config_knz, sollkonto, habenkonto, buchungstext,";
        $sql .= " betrag, mandant_id) values ('".$input['config_knz']."', '".$input['sollkonto']."', ";
        $sql .= "'".$input['habenkonto']."', '".$input['buchungstext']."', ".$input['betrag'].", ".$this->mandant_id.")";

        mysqli_query($db, $sql);
        $error = mysqli_error($db);
        if($error) {
           error_log($error);
           error_log($sql);
        }
        mysqli_close($db);
        return wrap_response("Fehler: $error");
    } else {
        mysqli_close($db);
        throw new ErrorException("Die uebergebene Schnellbuchungsvorlage ist nicht valide: ".$inputJSON);
    }
}

function updateQuickMenu($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    if($this->isValidQuickMenu($input)) {
        $sql = "update fi_quick_config set ";
        $sql .= "config_knz = '".$input['config_knz']."', ";
        $sql .= "buchungstext = '".$input['buchungstext']."', ";
        $sql .= "sollkonto = '".$input['sollkonto']."', ";
        $sql .= "habenkonto = '".$input['habenkonto']."', ";
        $sql .= "betrag = '".$input['betrag']."' ";
        $sql .= "where mandant_id = ".$this->mandant_id;
        $sql .= " and config_id = ".$input['config_id'];

        mysqli_query($db, $sql);
        $error = mysqli_error($db);
        if($error) {
            error_log($error);
            error_log($sql);
            mysqli_close($db);
            return wrap_response("Fehler: $error");
        } else {
            mysqli_close($db);
            return wrap_response("Gelöscht");
        }
    } else {
        mysqli_close($db);
        throw new ErrorException("Die uebergebene Schnellbuchungsvorlage ist nicht valide: ".$inputJSON);
    }
}

function removeQuickMenu($request) {
    $db = getDbConnection();
    $id = $request['id'];
    if(is_numeric($id)) {
        $sql =  "delete from fi_quick_config where mandant_id = $this->mandant_id";
        $sql .= " and config_id = $id";

        mysqli_query($db, $sql);
        mysqli_close($db);

        return wrap_response(null);
    } else {
        throw new ErrorException("Die id der Schnellbuchungsvorlage muss numerisch sein!");
    }
}

# Prüft ob $menu ein valides QuickMenu-Objekt ist
# Typen und Felder prüfen
function isValidQuickMenu($menu) {
    if(count($menu) < 4 && count($menu) > 7) {
        return false;
    } 
    foreach($menu as $key => $value) {
        if(!$this->isValidFieldAndValue($key, $value)) return false;
    }
    return true;
}

# Prüft ein einzelnes Feld uns seinen Inhalt auf Gültigkeit
function isValidFieldAndValue($key, $value) {
    switch($key) {
        case 'config_id': 
        case 'sollkonto':
        case 'habenkonto': 
        case 'betrag':
        case 'mandant_id':
            return $value == null || is_numeric($value);
        case 'buchungstext':
        case 'config_knz':
            $pattern = '/[\']/';
            preg_match($pattern, $value, $results);
            return count($results) == 0;
        default: // throw new ErrorException("Key: $key, Value: $value");
            return false;
    }
}
}
?>
