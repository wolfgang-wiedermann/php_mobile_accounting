<?php

class KontoController {

private $dispatcher, $mandant_id;

# Einsprungpunkt, hier übergibt das Framework
function invoke($action, $request, $dispatcher) {
    $this->dispatcher = $dispatcher;
    $this->mandant_id = $dispatcher->getMandantId();
    switch($action) {
        case "get":
            return $this->getKonto($request['id']);
        case "list":
            return $this->getKonten();
        case "save":
            return $this->saveKonto($request);
        case "create":
            return $this->createKonto($request);
        case "saldo":
            return $this->getSaldo($request['id']);
        case "monatssalden":
            return $this->getMonatsSalden($request['id']);
        default:
            throw new ErrorException("Unbekannte Action");
    }
}

# Liest eines einzelnes Konto aus und liefert
# sie als Objekt zurück
function getKonto($id) {
    if(is_numeric($id)) {
        $db = getDbConnection();
        $rs = mysqli_query($db, "select * from fi_konto where kontonummer = $id and mandant_id = $this->mandant_id");
        $erg = mysqli_fetch_object($rs);
        mysqli_close($db); 
        return $erg;
    } else throw Exception("Kontonummer nicht numerisch");
}

# Ermittelt den aktuellen Saldo des Kontos
function getSaldo($id) {
    if(is_numeric($id)) {
        $db = getDbConnection();
        $rs = mysqli_query($db, "select saldo from fi_ergebnisrechnungen where mandant_id = $this->mandant_id and konto = '$id'");
        $erg = mysqli_fetch_object($rs);
        mysqli_close($db);
        return $erg->saldo;
    } else throw Exception("Kontonummer nicht numerisch");
}

# Ermittelt die Monats-Salden des Kontos
function getMonatsSalden($kontonummer) {
    if(is_numeric($kontonummer)) {
        $db = getDbConnection();
        $rs = mysqli_query($db, "select kontenart_id from fi_konto where kontonummer = '$kontonummer'");
        if($kontenart_id = mysqli_fetch_object($rs)->kontenart_id) {
            mysqli_free_result($rs);
            if($kontenart_id == 3 || $kontenart_id == 4) {
                // Monatssummen, fuer Aufwands- und Ertragskonten
                $rs = mysqli_query($db, "select grouping, saldo from "
                      ."(select grouping, konto, sum(betrag) as saldo from "
                      ."(select (year(datum)*100)+month(datum) as grouping, konto, betrag "
                      ."from fi_buchungen_view where mandant_id = $this->mandant_id) as x "
                      ."group by grouping, konto) as y where y.konto = '$kontonummer'");
            } else {
                // Laufende Summen, fuer Bestandskonten
                $rs = mysqli_query($db, "select x1.grouping, sum(x2.betrag) as saldo "
                      ."from (select distinct (year(datum)*100)+month(datum) as grouping from fi_buchungen_view "
                      ."where mandant_id = '$this->mandant_id') x1 "
                      ."inner join (select (year(datum)*100+month(datum)) as grouping, konto, betrag "
                      ."from fi_buchungen_view where mandant_id = '$this->mandant_id') x2 "
                      ."on x2.grouping <= x1.grouping where konto = '$kontonummer' group by grouping, konto");
            }
            $result = array();
            while($obj = mysqli_fetch_object($rs)) {
                $result[] = $obj;
            }
            mysqli_free_result($rs);
            mysqli_close($db);
            return $result;
        } else {
            mysqli_close($db);
            throw new Exception("Kontonummer unbekannt");
        }
    } else throw Exception("Kontonummer nicht numerisch");
}

# Erstellt eine Liste aller Kontenarten
function getKonten() {
    $db = getDbConnection();
    $result = array();
    $rs = mysqli_query($db, "select * from fi_konto where mandant_id = $this->mandant_id order by kontenart_id, kontonummer");
    while($obj = mysqli_fetch_object($rs)) {
        $result[] = $obj;
    }
    mysqli_close($db);
    return $result;
}

# Speichert das als JSON-Objekt übergebene Konto
function saveKonto($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    if($this->isValidKonto($input)) { 
        $sql = "update fi_konto set bezeichnung = '".$input['bezeichnung']."', kontenart_id = ".$input['kontenart_id']
              ." where kontonummer = ".$input['kontonummer']." and mandant_id = ".$this->mandant_id;
        mysqli_query($db, $sql);
        mysqli_close($db);
        return $void = array();
    } else {
        throw new Exception("Kontenobjekt enthaelt ungueltige Zeichen");
    }
}

# legt das als JSON-Objekt übergebene Konto an
function createKonto($request) {
    $db = getDbConnection();
    $inputJSON = file_get_contents('php://input');
    $input = json_decode( $inputJSON, TRUE );
    if($this->isValidKonto($input)) {
        $sql = "insert into fi_konto (kontonummer, bezeichnung, kontenart_id, mandant_id) values ('"
              .$input['kontonummer']."', '".$input['bezeichnung']
              ."', ".$input['kontenart_id'].", ".$this->mandant_id.")";
        mysqli_query($db, $sql);
        mysqli_close($db);
        return $void = array();
    } else {
        throw new Exception("Kontenobjekt enthaelt ungueltige Zeichen");
    }
}

# Prüft, ob das angegebene Konto valide ist
# (passende Typen, richtige Felder etc.)
function isValidKonto($konto) {
    if(count($konto) < 3 && count($konto) > 4) {
        return false;
    }
    foreach($konto as $key => $value) {
        if(!$this->isValidFieldAndValue($key, $value)) return false;
    }
    return true;
}

# Prüft ein einzelnes Feld und seinen Inhalt auf Gültigkeit
function isValidFieldAndValue($key, $value) {
    switch($key) {
        case 'kontonummer': 
        case 'kontenart_id':
        case 'mandant_id': 
            return is_numeric($value);
        case 'bezeichnung':
        case 'tostring':
            $pattern = '/[\']/';
            preg_match($pattern, $value, $results);
            return count($results) == 0;
        default:
            return false;
    }
}

}

?>
