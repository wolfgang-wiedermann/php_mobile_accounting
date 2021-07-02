<?php
/*
 * Copyright (c) 2015 by Wolfgang Wiedermann
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

# Controller für die globale Konfiguration des Haushaltsbuchs
class ConfigController {

    private $dispatcher, $mandant_id;

    function invoke($action, $request, $dispatcher) {
        $this->dispatcher = $dispatcher;
        $this->mandant_id = $dispatcher->getMandantId();
        switch($action) {
            case 'list':
                return $this->listConfigEntries();
            case 'update':
                return $this->updateConfigEntry();
            case 'get':
                return $this->getConfigEntry($request);
            default:
                throw new ErrorException("Unbekannte Action");
        }
    }

    function listConfigEntries() {
        $pdo = getPdoConnection();
        $lst = array();
        $stmt = $pdo->prepare("select * from fi_config_params where mandant_id = :mandant_id order by param_desc");
        $stmt->execute(array(
            "mandant_id" => $this->mandant_id
        ));

        while($obj = $stmt->fetchObject()) {
            $lst[] = $obj;
        }

        return wrap_response($lst);
    }


    function getConfigEntry($request) {
        $pdo = getPdoConnection();
        if(!isset($request['param_id'])) {
            throw new ErrorException("Parameter param_id nicht im Request enthalten");
        }
        $id = $request['param_id'];
        if(is_numeric($id)) {
            $stmt = $pdo->prepare("select * from fi_config_params where mandant_id = :mandant_id and param_id = :id");
            $stmt->execute(array(
                "mandant_id" => $this->mandant_id,
                "id" => $id
            ));
            if($obj = $stmt->fetchObject()) {
                return wrap_response($obj);
            } else {
                return wrap_response(null);
            }
        } else {
            throw new ErrorException("Die fi_config_entries.param_id ist fehlerhaft");
        }
    }

    function updateConfigEntry() {
        $pdo = getPdoConnection();
        $inputJSON = file_get_contents('php://input');
        $input = json_decode( $inputJSON, TRUE );
        if($this->isValidConfigEntry($input)) {

            $query = new QueryHandler("configentry_update.sql");
            $sql = $query->getSql();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                "mandant_id" => $this->mandant_id,
                "param_knz" => $input["param_knz"],
                "param_desc" => $input["param_desc"],
                "param_value" => $input["param_value"],
                "param_id" => $input["param_id"]
            ));
           
            return wrap_response("...");
        } else {
            mysqli_close($db);
            throw new ErrorException("Der übergebene Konfigurationsparameter ist nicht valide: ".$inputJSON);
        }
    }


# Prüft ob $menu ein valides QuickMenu-Objekt ist
# Typen und Felder prüfen
    private function isValidConfigEntry($menu) {
        if(count($menu) < 4 && count($menu) > 6) {
            return false;
        }
        foreach($menu as $key => $value) {
            if(!$this->isValidFieldAndValue($key, $value)) return false;
        }
        return true;
    }

# Prüft ein einzelnes Feld uns seinen Inhalt auf Gültigkeit
    private function isValidFieldAndValue($key, $value) {
        switch($key) {
            case 'mandant_id':
            case 'param_id':
                return $value == null || is_numeric($value);
            case 'param_knz':
            case 'param_desc':
            case 'param_value':
                $pattern = '/[\']/';
                preg_match($pattern, $value, $results);
                return count($results) == 0;
            case 'description':
                return true;
            default:
                return false;
        }
    }
}
?>
