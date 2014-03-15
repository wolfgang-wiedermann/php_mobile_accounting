<?php
#
# Generierung von Testdaten zur Bewertung der Einflüsse
# der Datenmenge in der buchungen-Tabelle auf das
# Antwortzeitverhalten.
#
require_once("../lib/Database.php");
$db = getDbConnection();

$mandantenzahl = 500;

$aktivkonten = array();
$passivkonten = array();
$aufwandkonten = array();
$ertragskonten = array();

$rs = mysqli_query($db, "select * from fi_konto");

while($obj = mysqli_fetch_object($rs)) {
    if($obj->kontenart_id == 1) {
        $aktivkonten[] = $obj;
    } else if($obj->kontenart_id == 2) {
        $passivkonten[] = $obj;
    } else if($obj->kontenart_id == 3) {
        $aufwandkonten[] = $obj;
    } else if($obj->kontenart_id == 4) {
        $ertragskonten[] = $obj;
    }
}

mysqli_free_result($rs);

for($i = 0; $i < 100000; $i++) {
    $mandant_id = rand(1, $mandantenzahl);
    $datum = "2015-".rand(1, 12)."-01";
    if($i % 4 == 0) {
        $sollkonto = $aktivkonten[rand(0, sizeof($aktivkonten)-1)]->kontonummer;
        $habenkonto = $ertragskonten[rand(0, sizeof($ertragskonten)-1)]->kontonummer;
    } else if($i % 4 == 1) {
        $sollkonto = $aufwandkonten[rand(0, sizeof($aufwandkonten)-1)]->kontonummer;
        $habenkonto = $aktivkonten[rand(0, sizeof($aktivkonten)-1)]->kontonummer;
    } else if($i % 4 == 2) {
        $sollkonto = $aktivkonten[rand(0, sizeof($aktivkonten)-1)]->kontonummer;
        $habenkonto = $ertragskonten[rand(0, sizeof($ertragskonten)-1)]->kontonummer;
    } else if($i % 4 == 3) {
        $sollkonto = $aufwandkonten[rand(0, sizeof($aufwandkonten)-1)]->kontonummer;
        $habenkonto = $aktivkonten[rand(0, sizeof($aktivkonten)-1)]->kontonummer;
    }    

    $sql = "insert into fi_buchungen (mandant_id, buchungstext, sollkonto, habenkonto, betrag, datum, bearbeiter_user_id) ";
    $sql .= "values('$mandant_id', 'Testbuchung', '$sollkonto', '$habenkonto', 12.34, '$datum', -1);\n";

    mysqli_query($db, $sql);
}
mysqli_query($db, $sql);

mysqli_close($db);
?>
