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

class BuchungController {

private $dispatcher, $mandant_id;

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $dispatcher) {

    $this->dispatcher = $dispatcher;
    $this->mandant_id = $dispatcher->getMandantId();

    switch($action) {
        case "create":
            return $this->createBuchung($request);
        case "aktuellste":
            return $this->getTop25();
        case "listbykonto":
            return $this->getListByKonto($request);
        case "listoffeneposten":
            return $this->getOpList();
        case "closeop":
            return $this->closeOpAndGetList($request);
        default:
            $message = array();
            $message['message'] = "Unbekannte Action";
            return $message;
    }
}

# legt das als JSON-Objekt übergebene Konto an
function createBuchung($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    $result = $this->createBuchungInternal($input, $db);
    mysqli_close($db);
    return $result;
}

# Innerhalb dieses Controllers wiederverwendbare Funktion zum
# Anlegen von Buchungen
private function createBuchungInternal($input, $db) {
    if($this->isValidBuchung($input)) {
        if($input['is_offener_posten']) {
            $temp_op = 1;
        } else {
            $temp_op = 0;
        }

        $sql = "insert into fi_buchungen (mandant_id, buchungstext, sollkonto, habenkonto, "
            ."betrag, datum, bearbeiter_user_id, is_offener_posten)"
            ." values ($this->mandant_id, '".$input['buchungstext']
            ."', '".$input['sollkonto']."', '".$input['habenkonto']."', ".$input['betrag'].", '"
            .$input['datum']."', ".$this->dispatcher->getUserId().", ".$temp_op.")";
        mysqli_query($db, $sql);

        $empty = array();
        return wrap_response($empty, "json");
    } else {
        throw new ErrorException("Das Buchungsobjekt enthält nicht gültige Elemente");
    }

}

# liest die aktuellsten 25 Buchungen aus
function getTop25() {
    $db = getDbConnection();
    $top = array();
    $rs = mysqli_query($db, "select * from fi_buchungen "
        ."where mandant_id = $this->mandant_id "
        ."order by buchungsnummer desc limit 25");

    while($obj = mysqli_fetch_object($rs)) {
        $top[] = $obj;
    }

    mysqli_close($db);
    return wrap_response($top);
}

# liest die offenen Posten aus
function getOpList() {
    $db = getDbConnection();
    $top = array();
    $rs = mysqli_query($db, "select * from fi_buchungen "
        ."where mandant_id = $this->mandant_id "
        ."and is_offener_posten = 1 "
        ."order by buchungsnummer");

    while($obj = mysqli_fetch_object($rs)) {
        $top[] = $obj;
    }

    mysqli_close($db);
    return wrap_response($top);
}

# liest die offenen Posten aus
function closeOpAndGetList($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    if($this->isValidOPCloseRequest($input)) {
        $db->begin_transaction();
        try {
            // Buchung anlegen
            $buchung = $input['buchung'];
            $this->createBuchungInternal($buchung, $db);
            // Offener-Posten-Flag auf false setzen
            $buchungsnummer = $input['offenerposten'];
            if (is_numeric($buchungsnummer)) {
                $sql = "update fi_buchungen set is_offener_posten = 0"
                    . " where mandant_id = $this->mandant_id "
                    . " and buchungsnummer = $buchungsnummer";
                mysqli_query($db, $sql);
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
        // Aktualisierte Offene-Posten-Liste an den Client liefern
        $top = array();
        $rs = mysqli_query($db, "select * from fi_buchungen "
            . "where mandant_id = $this->mandant_id "
            . "and is_offener_posten = 1 "
            . "order by buchungsnummer");

        while ($obj = mysqli_fetch_object($rs)) {
            $top[] = $obj;
        }
        mysqli_close($db);
        return wrap_response($top);
    } else {
        mysqli_close($db);
        throw new ErrorException("Der OP-Close-Request ist ungültig!");
    }
}

function getListByKonto($request) {
    $db = getDbConnection();
    $kontonummer = $request['konto'];
    # Nur verarbeiten, wenn konto eine Ziffernfolge ist, um SQL-Injections zu vermeiden
    if(is_numeric($kontonummer)) {

        $result = array();
        $result_list = array(); 

        // Buchungen laden
        $sql =  "SELECT buchungsnummer, buchungstext, habenkonto as gegenkonto, betrag, datum, is_offener_posten ";
        $sql .= "FROM fi_buchungen "; 
        $sql .= "WHERE mandant_id = $this->mandant_id and sollkonto = '$kontonummer' ";
        $sql .= "union ";
        $sql .= "select buchungsnummer, buchungstext, sollkonto as gegenkonto, betrag*-1 as betrag, datum, is_offener_posten ";
        $sql .= "from fi_buchungen ";
        $sql .= "where mandant_id = $this->mandant_id and habenkonto = '$kontonummer' ";
        $sql .= "order by buchungsnummer desc";

        $rs = mysqli_query($db, $sql);
        
        while($obj = mysqli_fetch_object($rs)) {
            $result_list[] = $obj;
        }
        $result['list'] = $result_list;

        // Saldo laden: 
        $sql =  "select sum(betrag) as saldo from (SELECT sum(betrag) as betrag from fi_buchungen ";
        $sql .= "where mandant_id = $this->mandant_id and sollkonto = '$kontonummer' ";
        $sql .= "union SELECT sum(betrag)*-1 as betrag from fi_buchungen ";
        $sql .= "where mandant_id = $this->mandant_id and habenkonto = '$kontonummer' ) as a ";

        $rs = mysqli_query($db, $sql);
        if($obj = mysqli_fetch_object($rs)) {
            $result['saldo'] = $obj->saldo;
        } else {
            $result['saldo'] = "unbekannt";
        }
        mysqli_close($db);
        return wrap_response($result);
    # Wenn konto keine Ziffernfolge ist, leeres Ergebnis zurück liefern
    } else {
        throw new ErrorException("Die Kontonummer ist nicht numerisch");
    }
}

# -----------------------------------------------------
# Eingabevalidierung
# -----------------------------------------------------

# Validiert ein Buchungsobjekt und prüft die Gültigkeit
# der einzelnen Felder des Objekts
function isValidBuchung($buchung) {
    if(count($buchung) < 6 && count($buchung) > 7) {
        return false;
    }
    foreach($buchung as $key => $value) {
        if(!$this->isInValidFields($key)) return false;
        if(!$this->isValidValueForField($key, $value)) return false;       
    }
    return true;
}

# Validiert ein OPCloseRequest-Objekt und prüft seine
# Gültigkeit (auch die zu schließende Buchungsnummer
# muss größer 0 sein!)
function isValidOPCloseRequest($request) {
    # Hauptgliederung prüfen
    if(!(isset($request['offenerposten'])
         && isset($request['buchung']))) {
        error_log("isValidOPCloseRequest: Hauptgliederung falsch");
        return false;
    }
    $op = $request['offenerposten'];
    $buchung = $request['buchung'];
    # Buchung prüfen
    if(!$this->isValidBuchung($buchung)) {
        error_log("isValidOPCloseRequest: Buchung invalide");
        return false;
    }
    # Offener Posten Buchungsnummer prüfen
    if(is_numeric($op) && $op != 0) {
        return true;
    } else {
        error_log("isValidOPCloseRequest: buchungsnummer == 0");
        error_log(print_r($op,true));
        return false;
    }
}

# Prüft, ob das gegebene Feld in der Menge der
# gueltigen Felder enthalten ist.
function isInValidFields($key) {
   switch($key) {
       case 'mandant_id':       return true;
       case 'buchungsnummer':   return true;
       case 'buchungstext':     return true;
       case 'sollkonto':        return true;
       case 'habenkonto':       return true;
       case 'betrag':           return true;
       case 'datum':            return true;
       case 'datum_de':         return true;
       case 'benutzer':         return true;
       case 'is_offener_posten':return true;
       default:                 return false;
   }
}

# Prüft, ob jeder Feldinhalt valide sein kann
function isValidValueForField($key, $value) {
   switch($key) {
       case 'buchungsnummer':
       case 'mandant_id':
       case 'betrag':
            return is_numeric($value);
       case 'sollkonto':
       case 'habenkonto':
            $pattern = '/[^0-9]/';
            preg_match($pattern, $value, $results);
            return count($results) == 0;
       case 'buchungstext':
       case 'datum':
       case 'datum_de':
            $pattern = '/[\']/';
            preg_match($pattern, $value, $results);
            return count($results) == 0;
       case 'is_offener_posten':
            return $value === false || $value === true;
       default: return true;
   }
}

}

?>
