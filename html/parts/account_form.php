<?php defined("MAIN_PAGE") or die("Fehlende Berechtigung, Seite darf nur aus index.php geladen werden"); ?>
<!-- Auswahlliste fuer Konten -->
<div id="account_form" class="content_form">
    <ul data-role="listview" data-filter="true" data-filter-placeholder="Search account" data-bind="foreach: konten">
        <li><a href="#" data-bind="text: tostring, attr: {'data-key': kontonummer}" class="konto_auswahl_button"></a></li>
    </ul>
    <br/>
    <button data-bind="click: $root.refreshKonten">Neu laden</button>
</div>
<!-- Operationsauswahl-Maske fuer Konten -->
<div id="account_form_operations" class="content_form">
   <ul data-role="listview">
       <li><a href="#" id="account_form_operations_show_saldo" class="account_form_operations_buttons">
           Saldo anzeigen</a></li>
       <li><a href="#" id="account_form_operations_show_salden" class="account_form_operations_buttons">
           Monatssalden</a></li>
       <li><a href="#" id="account_form_operations_show_buchungen" class="account_form_operations_buttons">
           Buchungen anzeigen</a></li>
       <li><a href="#" id="account_form_operations_edit_account" class="account_form_operations_buttons">
           Konto bearbeiten</a></li>
   </ul> 
</div>
<!-- Editor-Maske fuer Konten -->
<div id="account_form_edit" class="content_form">
    <label for="account_form_edit_kontonummer">Kontonummer</label>
    <input id="account_form_edit_kontonummer" data-bind="value: $root.konto().kontonummer" type="number"></input>
    <label for="account_from_edit_bezeichnung">Bezeichnung</label>
    <input id="account_from_edit_bezeichnung" data-bind="value: $root.konto().bezeichnung"></input>
    <label for="account_from_edit_kontenart">Kontenart</label>
    <select id="account_from_edit_kontenart" data-bind="value: $root.konto().kontenart_id, options: $root.kontenarten, optionsValue:'kontenart_id', optionsText: 'bezeichnung'"></select>
    <button id="account_form_edit_save_button" data-bind="click: $root.saveKonto">Speichern</button>
</div>
<!-- Anlegen-Maske fuer Konten -->
<div id="account_form_create" class="content_form">
    <label for="account_form_create_kontonummer">Kontonummer</label>
    <input id="account_form_create_kontonummer" data-bind="value: $root.konto().kontonummer" type="number"></input>
    <label for="account_from_create_bezeichnung">Bezeichnung</label>
    <input id="account_from_create_bezeichnung" data-bind="value: $root.konto().bezeichnung"></input>
    <label for="account_from_create_kontenart">Kontenart</label>
    <select id="account_from_create_kontenart" data-bind="value: $root.konto().kontenart_id, options: $root.kontenarten, optionsValue:'kontenart_id', optionsText: 'bezeichnung'"></select>
    <button id="account_form_create_save_button" data-bind="click: $root.createKonto">Speichern</button>
</div>
<!-- Buchungen anzeigen Maske -->
<div id="account_show_bookings" class="content_form">
</div>
<!-- Manatssalden-Diagramm Maske -->
<div id="account_show_monatssalden" class="content_form">
</div>
<!-- CODE -->
<script type="text/javascript">
var kontenForm = {

    selectedKontonummer : null,

    // EventHandler fuer account_form_operations registrieren
    registerAccountFormOperationsEvents : function() {
        $(".account_form_operations_buttons").unbind("click");
        $("#account_form_operations_show_saldo").click(kontenForm.showSaldoHandler);
        $("#account_form_operations_show_salden").click(kontenForm.showMonatsSaldenHandler);
        $("#account_form_operations_show_buchungen").click(kontenForm.showBuchungenHandler);
        $("#account_form_operations_edit_account").click(kontenForm.editAccountHandler);
    },
 
    // Handler fuer das Ereignis: Klick auf Konto in Liste
    kontoAuswahlHandler : function() {
        var key= this.getAttribute("data-key");
        console.log("found key: "+key);
        kontenForm.selectedKontonummer = key;
        $("#header_text").text("Konto: "+key);
        $(".content_form").hide();
        $("#account_form_operations").show();
        kontenForm.registerAccountFormOperationsEvents();
    },

    newKontoHandler : function() {
        $("#header_text").text("Neues Konto ...");
        $(".content_form").hide();
        $("#account_form_create").show();
        // Konto im Model noch leeren
        model.konto().kontonummer("");
        model.konto().bezeichnung("");
        model.konto().kontenart_id(1);
        $("#account_from_create_kontenart").selectmenu("refresh", true);
    },

    save : function(jsonString) {
        doPOST("konto", "save", jsonString,
            function(data) {
                alert("Konto erfolgreich gespeichert");
            },
            function(error) {
               alert("Fehler beim Speichern aufgetreten");
            }
        );
    },

    create : function(jsonString) {
        doPOST("konto", "create", jsonString,
            function(data) {
                alert("Konto erfolgreich angelegt");
            },
            function(error) {
               alert("Fehler beim Speichern aufgetreten");
            }
        );
    },


    showSaldoHandler : function() {
        doGET("konto", "saldo", {'id':kontenForm.selectedKontonummer}, 
            function(data) {
                alert("Saldo von Konto "+kontenForm.selectedKontonummer+" = "+data+" Euro");
            }, 
            function(error) {
                alert(JSON.stringify(error));
            }
        ); 
    },

    showMonatsSaldenHandler : function() {
        $(".content_form").hide();
        $("#account_show_monatssalden").show();
        $("#account_show_monatssalden").html("Monatssalden werden geladen");

        doGET("konto", "monatssalden", {'id':kontenForm.selectedKontonummer},
            function(data) {
                var table = "<table>";
                var code = "<b>Monatssalden: "+kontenForm.selectedKontonummer+"</b><br/>";
                code += '<div id="account_show_monatssalden_table"></div>';
                code += '<canvas id="account_show_monatssalden_canvas" width="300px" height="300px"></canvas>';
                $("#account_show_monatssalden").html(code);
                d.init("account_show_monatssalden_canvas");
                d.setToWindowWidth();
                var diagrammData = [];
                for(var key in data) {
                    diagrammData.push(data[key].saldo);
                    table += "<tr><td>"+data[key].grouping+"</td><td>"+data[key].saldo+"</td></tr>";
                }
                table += "</table>";
                $("#account_show_monatssalden_table").html(table);
                d.drawLineDiagramFor(diagrammData);
            }, 
            function(error) {
            }
        );
    },

    showBuchungenHandler : function() {
        $(".content_form").hide();
        $("#account_show_bookings").show();
        $("#account_show_bookings").html("wird geladen...");
        params = {'konto': kontenForm.selectedKontonummer};
        doGET("buchung", "listbykonto", params,
            function(data) {
                var tabelle = "<p>Saldo: "+data.saldo+"</p>";
                tabelle += "<table><tr><td>BNr</td><td>BTxt</td><td>GKto</td>";
                tabelle += "<td>Betrag</td><td>Datum</td></tr>";
                for(var key in data.list) {
                    var line = data.list[key];
                    tabelle += "<tr><td>"+line.buchungsnummer+"</td>";
                    tabelle += "<td>"+line.buchungstext+"</td>";
                    tabelle += "<td>"+line.gegenkonto+"</td>";
                    tabelle += "<td style=\"text-align:right;\">"+line.betrag+"</td>";
                    tabelle += "<td>"+line.datum+"</td></tr>";
                }
                tabelle += "</table>";
	            $("#account_show_bookings").html(tabelle);
            }, 
            function(error) {
                alert("Fehler beim Laden der Buchungen aufgetreten");
            }
        );
    },

    editAccountHandler : function() {
        $(".content_form").hide();
        $("#account_form_edit").show();
        handlers.loadKonto(kontenForm.selectedKontonummer);
    },

};

</script>
