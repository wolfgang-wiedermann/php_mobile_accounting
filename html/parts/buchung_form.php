<?php defined("MAIN_PAGE") or die("Fehlende Berechtigung, Seite darf nur aus index.php geladen werden"); ?>
<!-- Funktionsauswahl - Buchungen -->
<div id="buchung_form" class="content_form">
<ul data-role="listview">
    <li><a href="#" id="buchung_form_erfassen" class="buchung_form_item">Buchung erfassen</a></li>
    <li><a href="#" id="buchung_form_newest" class="buchung_form_item">Aktuellste Buchungen</a></li>
<!--
    <li><a href="#" id="buchung_form_journal_temporal" class="buchung_form_item">Buchungen von bis</a></li>
-->
</ul>
</div>
<!-- Formular zur Buchungseingabe -->
<div id="buchung_form_erfassung" class="content_form">
    <label for="buchung_form_erfassung_buchungstext">Buchungstext</label>
    <input id="buchung_form_erfassung_buchungstext" data-bind="value: $root.buchung().buchungstext"></input>
    <label for="buchung_form_erfassung_sollkonto">Soll-Konto</label>
    <select id="buchung_form_erfassung_sollkonto" data-bind="value: $root.buchung().sollkonto, options: konten, optionsText: 'tostring', optionsValue: 'kontonummer'"></select>
    <label for="buchung_form_erfassung_habenkonto">Haben-Konto</label>
    <select id="buchung_form_erfassung_habenkonto" data-bind="value: $root.buchung().habenkonto, options: konten, optionsText: 'tostring', optionsValue: 'kontonummer'"></select>
    <label for="buchung_form_erfassung_betrag">Betrag</label>
    <input id="buchung_form_erfassung_betrag" data-bind="value: $root.buchung().betrag" type="number"></input>
    <label for="buchung_form_erfassung_datum">Buchungsdatum</label>
    <input id="buchung_form_erfassung_datum" data-bind="value: $root.buchung().datum" type="date"></input>
    <br/>
    <button id="buchung_form_erfassung_button_save" data-bind="click: $root.saveBuchung">Speichern</button>
</div>
<!-- Anzeige der aktuellsten 25 Buchungen -->
<div id="buchung_form_current" class="content_form">
</div>
<!-- JavaScript-Code -->
<script type="text/javascript">
var buchungenForm = {

registerBuchungFormEvents : function() {
    $(".buchung_form_item").unbind("click");
    $("#buchung_form_erfassen").click(buchungenForm.showErfassen);
    $("#buchung_form_newest").click(buchungenForm.showAktuellste);
//    $("#buchung_form_journal_temporal").click(buchungenForm.showBuchungenVonBis);
},    

showErfassen : function() {
    $(".content_form").hide();
    // Aktuelles Datum setzen
    model.buchung().datum(JSON.stringify(new Date()).substring(1,11));
    $("#buchung_form_erfassung").show();
},

showAktuellste : function() {
    $(".content_form").hide();
    $("#buchung_form_current").show();
    doGETwithCache("buchung", "aktuellste", {}, 
        function(data) {
            var htmlAkt = "<b>Aktuellste Buchungen</b><br/><br/><table>";
            for(var key in data) {
                var buchung = data[key];
                htmlAkt += "<tr><td>"+buchung.buchungsnummer+"</td>";
                htmlAkt += "<td colspan=\"3\">"+buchung.buchungstext.substring(0, 30);
		if(buchung.buchungstext.length > 30) htmlAkt += "...";
                htmlAkt += "</td></tr>";
                htmlAkt += "<tr><td>"+buchung.sollkonto+"</td><td>"+buchung.habenkonto+"</td><td>";
                htmlAkt += buchung.betrag+"</td><td>"+buchung.datum+"</td></tr>\n";
            }
            htmlAkt += "<table>";
            $("#buchung_form_current").html(htmlAkt);
        },
        function(error) {
            alert("Fehler aufgetreten: "+JSON.stringify(error));
        }
    );
},

create : function(jsonString) {
    doPOSTwithQueue("buchung", "create", jsonString,
        function(data) {
	    // Buchung erfolgreich angelegt.
            alert("Buchung erfolgreich angelegt");
            console.log(JSON.stringify(data));
        },
        function(error) {
            alert("Fehler beim Anlegen der Buchung aufgetreten: "+JSON.stringify(error));
        }
    );
},

};
</script>
