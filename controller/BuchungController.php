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
    $pdo = getPdoConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    $result = $this->createBuchungInternal($input, $pdo);
    return $result;
}

# Innerhalb dieses Controllers wiederverwendbare Funktion zum
# Anlegen von Buchungen
private function createBuchungInternal($input, $pdo) {
    if($this->isValidBuchung($input)) {
        if($input['is_offener_posten']) {
            $temp_op = 1;
        } else {
            $temp_op = 0;
        }

        $query = new QueryHandler("buchung_insert.sql");
        $sql = $query->getSql();
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            "mandant_id" => $this->mandant_id,
            "buchungstext" => $input["buchungstext"],
            "sollkonto" => $input["sollkonto"],
            "habenkonto" => $input["habenkonto"],
            "betrag" => $input["betrag"],
            "datum" => $input["datum"],
            "bearbeiter_user_id" => $this->dispatcher->getUserId(),
            "is_offener_posten" => $temp_op
        ));

        $empty = array();
        return wrap_response($empty, "json");
    } else {
        throw new ErrorException("Das Buchungsobjekt enthält nicht gültige Elemente");
    }

}

# liest die aktuellsten 25 Buchungen aus
function getTop25() {
    $pdo = getPdoConnection();
    $top = array();

    $query = new QueryHandler("buchung_get_top25.sql");
    $sql = $query->getSql();
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        "mandant_id" => $this->mandant_id
    ));

    while($obj = $stmt->fetchObject()) {
        $top[] = $obj;
    }

    return wrap_response($top);
}

# liest die offenen Posten aus
function getOpList() {
    $pdo = getPdoConnection();
    $top = array();

    $query = new QueryHandler("buchung_get_oplist.sql");
    $sql = $query->getSql();
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        "mandant_id" => $this->mandant_id
    ));

    while($obj = $stmt->fetchObject()) {
        $top[] = $obj;
    }

    return wrap_response($top);
}

# liest die offenen Posten aus
function closeOpAndGetList($request) {
    $pdo = getPdoConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    if($this->isValidOPCloseRequest($input)) {
        $pdo->beginTransaction();
        try {
            // Buchung anlegen
            $buchung = $input['buchung'];
            $this->createBuchungInternal($buchung, $pdo);
            // Offener-Posten-Flag auf false setzen
            $buchungsnummer = $input['offenerposten'];
            if (is_numeric($buchungsnummer)) {
                $query = new QueryHandler("buchung_close_op_update.sql");
                $sql = $query->getSql();
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    "mandant_id" => $this->mandant_id,
                    "buchungsnummer" => $buchungsnummer
                ));
            } else {
                $pdo->rollBack();
            }
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        // Aktualisierte Offene-Posten-Liste an den Client liefern
        $top = array();
        $query = new QueryHandler("buchung_get_oplist.sql");
        $sql = $query->getSql();
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            "mandant_id" => $this->mandant_id
        ));

        while($obj = $stmt->fetchObject()) {
            $top[] = $obj;
        }
        return wrap_response($top);
    } else {
        mysqli_close($db);
        throw new ErrorException("Der OP-Close-Request ist ungültig!");
    }
}

function getListByKonto($request) {
    $pdo = getPdoConnection();
    $kontonummer = $request['konto'];
    $jahr = $request['jahr'];
    # Nur verarbeiten, wenn konto eine Ziffernfolge ist, um SQL-Injections zu vermeiden
    if(is_numeric($kontonummer) && is_numeric($jahr)) {

        $result = array();
        $result_list = array(); 

        // Buchungen laden
        $query = new QueryHandler("buchung_list_by_konto_entries.sql");
        $sql = $query->getSql();
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            "mandant_id" => $this->mandant_id,
            "kontonummer" => $kontonummer,
            "jahr" => $jahr
        ));
        
        while($obj = $stmt->fetchObject()) {
            $result_list[] = $obj;
        }

        $result['list'] = $result_list;

        // Saldo laden: 
        $query = new QueryHandler("buchung_list_by_konto_saldo.sql");
        $sql = $query->getSql();
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            "mandant_id" => $this->mandant_id,
            "kontonummer" => $kontonummer
        ));

        if($obj = $stmt->fetchObject()) {
            $result['saldo'] = $obj->saldo;
        } else {
            $result['saldo'] = "unbekannt";
        }

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
