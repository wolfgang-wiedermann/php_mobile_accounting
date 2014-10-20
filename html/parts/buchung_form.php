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
?>
<?php defined("MAIN_PAGE") or die("Fehlende Berechtigung, Seite darf nur aus index.php geladen werden"); ?>
<!-- Funktionsauswahl - Buchungen -->
<div id="buchung_form" class="content_form">
<ul data-role="listview">
    <li><a href="#" id="buchung_form_erfassen" class="buchung_form_item">Buchung erfassen</a></li>
    <li><a href="#" id="buchung_form_newest" class="buchung_form_item">Aktuellste Buchungen</a></li>
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
    <input id="buchung_form_erfassung_betrag" data-bind="value: $root.buchung().betrag"  type="number" step="0.01"></input>
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
                htmlAkt += "<td colspan=\"3\">"+util.escapeGtLt(buchung.buchungstext.substring(0, 30));
		if(buchung.buchungstext.length > 30) htmlAkt += "...";
                htmlAkt += "</td></tr>";
                htmlAkt += "<tr><td>"+buchung.sollkonto+"</td><td>"+buchung.habenkonto+"</td>";
                htmlAkt += "<td class=\"td_betrag\">"+buchung.betrag+"</td>";
                htmlAkt += "<td>"+util.formatDateAtG(buchung.datum)+"</td></tr>\n";
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
