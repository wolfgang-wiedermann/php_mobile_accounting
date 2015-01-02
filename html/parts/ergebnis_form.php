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
<div id="ergebnis_form" class="content_form">
<ul data-role="listview">
    <li data-role="list-divider">Standard-Auswertungen</li>
    <li><a href="#bilanz" id="ergebnis_action_bilanz" class="ergebnis_form_item">Bilanz</a></li>
    <li><a href="#guv" id="ergebnis_action_guv" class="ergebnis_form_item">Gewinn und Verlust</a></li>
    <li><a href="#guv_month" id="ergebnis_action_guv_month" class="ergebnis_form_item">GuV aktueller Monat</a></li>
    <li><a href="#guv_prognose" id="ergebnis_action_guv_prognose" class="ergebnis_form_item">GuV Prognose</a></li>
    <li data-role="list-divider">Verlaufs-Auswertungen</li>
    <li><a href="#verlauf_aufwand" id="ergebnis_action_verlauf_aufwand" class="ergebnis_form_item">Aufwand (Monate)</a></li>
    <li><a href="#verlauf_ertrag" id="ergebnis_action_verlauf_ertrag" class="ergebnis_form_item">Ertrag (Monate)</a></li>
    <li><a href="#verlauf_gewinn" id="ergebnis_action_verlauf_gewinn" class="ergebnis_form_item">Gewinn (Monate)</a></li>
    <li><a href="#verlauf_intramonth" id="ergebnis_action_intramonth" class="ergebnis_form_item">Monatsinterner Verlauf</a></li>
    <li><a href="#cacheflow" id="ergebnis_action_cacheflow" class="ergebnis_form_item">Zu- und Abfluss (Monate)</a></li>
    <li><a href="#verlauf_frei" id="ergebnis_action_verlauf_frei" class="ergebnis_form_item">Frei kombiniert (Monate)</a></li>
    <li data-role="list-divider">Datenexport</li>
    <li><a href="#export" id="ergebnis_action_export_journal" class="ergebnis_form_item">Journal exportieren</a></li>
    <li><a href="#export" id="ergebnis_action_export_guv_monate" class="ergebnis_form_item">GuV Monate exportieren</a></li>
</ul>
</div>
<!-- Bilanz -->
<div id="ergebnis_form_bilanz" class="content_form">
</div>
<!-- GuV-Prognose -->
<div id="ergebnis_form_guv_prognose" class="content_form">
</div>
<!-- GuV -->
<div id="ergebnis_form_guv" class="content_form">
<div id="ergebnis_form_guv_controls_m" class="ergebnis_form_guv_controls">
    <table><tr><td style="width:100%">
    <select id="ergebnis_form_guv_months" 
            data-bind="value: $root.selectedMonat, options: buchungsmonate, optionsText: 'monat', optionsValue: 'monat'">
    </select></td><td>
    <a id="ergebnis_form_guv_months_refresh" data-role="button" data-bind="click:$root.updateMonateSimple"
               data-icon="refresh" data-inline="true" data-iconpos="notext"></a>
    </td></tr></table>
</div>
<div id="ergebnis_form_guv_controls_y" class="ergebnis_form_guv_controls">
    <select id="ergebnis_form_guv_years"
            data-bind="value: $root.selectedYear, options: buchungsjahre, optionsText: 'jahr', optionsValue: 'jahr'">
    </select>
</div>
<div id="ergebnis_form_guv_inner">
</div>
</div>
<!-- Verlauf -->
<div id="ergebnis_form_verlauf" class="content_form">
</div>
<!-- Verlauf Intra-Month -->
<div id="ergebnis_form_verlauf_intra" class="content_form">
    <select id="ergebnis_form_intra_months"
            data-bind="value: $root.selectedMonat, options: buchungsmonate, optionsText: 'monat', optionsValue: 'monat'">
    </select>
    <div id="ergebnis_form_verlauf_intra_inner"> 
    </div>
</div>
<!-- Verlaufsauswertung frei, Vorauswahl -->
<div id="ergebnis_form_verlauf_vorauswahl" class="content_form">
    <h2>Ausw&auml;hlbare Konten</h2>
    <ul data-role="listview" data-filter="true" data-filter-placeholder="Suchen..." 
        data-bind="foreach: konten_selectable, jqmRefreshList: konten_selectable">
        <li><a href="#" data-bind="text: tostring, attr: {'data-key': kontonummer}, click: $root.selectKonto"></a></li>
    </ul><br/>
    <h2>Ausgew&auml;hlte Konten</h2>
    <ul data-role="listview" data-bind="foreach: konten_selected, jqmRefreshList: konten_selected">
        <li><a href="#" data-bind="text: tostring, attr: {'data-key': kontonummer}, click: $root.unselectKonto"></a></li>
    </ul>
    <br/>
    <button id="ergebnis_action_verlauf_frei_anzeigen" class="ergebnis_form_item">Anzeigen</button>
</div>
<!-- Verlaufsauswertung Cashflow, Vorauswahl Aktivkonten -->
<div id="ergebnis_form_verlauf_cashflow_vorauswahl" class="content_form">
    <select data-bind="value: $root.sollhaben">
        <option value="S">Soll-Buchungen</option>
        <option value="H">Haben-Buchungen</option>
    </select>
    <h2>Aktivkonten</h2>
    <ul data-role="listview" data-folter="true" data-filter-placeholder="Suchen..."
        data-bind="foreach: konten_aktiv, jqmRefreshList: konten_aktiv">
        <li><a href="#" data-bind="text: tostring, attr: {'data-key': kontonummer}, click: $root.selectKontoForCashFlow"></a></li>
    </ul>
</div>
<!-- JavaScript-Code -->
<script type="text/javascript">
var ergebnisForm = {

constants : {
    KONTENART : 1, 
    FREIE_KONTEN : 2
},

registerErgebnisFormEvents : function() {
    $(".ergebnis_form_item").unbind("click");
    // Basisauswertungen
    $("#ergebnis_action_bilanz").click(ergebnisForm.showBilanz);
    $("#ergebnis_action_guv").click(ergebnisForm.showGuV);
    $("#ergebnis_action_guv_month").click(ergebnisForm.showGuVMonth);
    $("#ergebnis_action_guv_prognose").click(ergebnisForm.showGuVPrognose);
    // Verlaufsauswertungen
    $("#ergebnis_action_verlauf_aufwand").click(ergebnisForm.showVerlaufAufwand);
    $("#ergebnis_action_verlauf_ertrag").click(ergebnisForm.showVerlaufErtrag);
    $("#ergebnis_action_verlauf_gewinn").click(ergebnisForm.showVerlaufGewinn);
    $("#ergebnis_action_intramonth").click(ergebnisForm.showVerlaufIntraMonth);
    $("#ergebnis_action_verlauf_frei").click(ergebnisForm.showVerlaufFreiVorauswahl);
    $("#ergebnis_action_cacheflow").click(ergebnisForm.showVerlaufCacheFlow);
    $("#ergebnis_action_verlauf_frei_anzeigen").click(ergebnisForm.showVerlaufFrei);
    // Datenexport
    $("#ergebnis_action_export_journal").click(ergebnisForm.exportJournal);
    $("#ergebnis_action_export_guv_monate").click(ergebnisForm.exportGuvMonate);

    $("#ergebnis_form_guv_months").unbind("change");
    $("#ergebnis_form_guv_months").change(ergebnisForm.loadGuVMonth);

    $("#ergebnis_form_intra_months").unbind("change");
    $("#ergebnis_form_intra_months").change(ergebnisForm.loadVerlaufIntraMonth);

    $("#ergebnis_form_guv_years").unbind("change");
    $("#ergebnis_form_guv_years").change(ergebnisForm.loadGuV);
},    

showBilanz : function() {
    $(".content_form").hide();
    $("#ergebnis_form_bilanz").show();
    ergebnisForm.loadBilanz();
},

showGuV : function() {
    $(".content_form").hide();
    $("#ergebnis_form_guv").show();
    ergebnisForm.loadGuV();
},

showGuVMonth : function() {
    $(".content_form").hide();
    $("#ergebnis_form_guv").show();
    ergebnisForm.loadGuVMonth();
},

showGuVPrognose : function() {
    $(".content_form").hide();
    $("#ergebnis_form_guv_prognose").show();
    ergebnisForm.loadGuVPrognose();
},

showVerlaufAufwand : function() {
    $(".content_form").hide();
    $("#ergebnis_form_verlauf").show();
    ergebnisForm.loadVerlaufKontenart(3);
},

showVerlaufErtrag : function() {
    $(".content_form").hide();
    $("#ergebnis_form_verlauf").show();
    ergebnisForm.loadVerlaufKontenart(4);
},

showVerlaufIntraMonth : function() {
    $(".content_form").hide();
    $("#ergebnis_form_intra_months").selectmenu('refresh');
    $("#ergebnis_form_verlauf_intra").show();
    ergebnisForm.loadVerlaufIntraMonth();
},

showVerlaufGewinn : function() {
    $(".content_form").hide();
    $("#ergebnis_form_verlauf").show();
    ergebnisForm.loadVerlaufKontenart(-1);
},

showVerlaufFreiVorauswahl : function() {
    $(".content_form").hide();
    $("#ergebnis_form_verlauf_vorauswahl").show();
},

showVerlaufFrei : function() {
    $(".content_form").hide();
    $("#ergebnis_form_verlauf").show();
    ergebnisForm.loadVerlaufFrei();
},

showVerlaufCacheFlow : function() {
    $(".content_form").hide();
    $("#ergebnis_form_verlauf_cashflow_vorauswahl").show();
},

loadBilanz : function() {
    doGETwithCache("ergebnis", "bilanz", [], 
        function(data) {
            var html = "Bilanzdarstellung:<br/>";
            html += "<table>";
            for(var key in data.zeilen) {
                var line = data.zeilen[key];
                html += "<tr><td>"+line.konto+"</td><td>"+line.kontenname+"</td>";
                html += "<td class=\"td_betrag\">"+line.saldo+"</td></tr>";
            }
            html += "</table>";
            html += "<br/><b>Ergebnis:</b><br/><table>";
            for(var key in data.ergebnisse) {
                var erg = data.ergebnisse[key];
                var bezeichnung;
                if(erg.kontenart_id === '1') bezeichnung = 'Aktiva';
                else if(erg.kontenart_id === '2') bezeichnung = 'Passiva';
                else if(erg.kontenart_id === '5') bezeichnung = 'Saldo'; 
                html += "<tr><td>"+bezeichnung+"</td><td>"+erg.saldo+"</td></tr>";
            }
            html += "</table>";
            $("#ergebnis_form_bilanz").html(html);
        }, 
        function(error) {
            alert(error);
        }
    );
},

loadGuV : function() {
    $(".ergebnis_form_guv_controls").hide();
    $("#ergebnis_form_guv_controls_y").show();

    params = {'year': $("#ergebnis_form_guv_years").val()};

    doGETwithCache("ergebnis", "guv", params, 
        function(data) {
            var html = "Gewinn und Verlust:<br/><table>";
            for(var key in data.zeilen) {
                var line = data.zeilen[key];
                html += "<tr><td>"+line.konto+"</td><td>"+line.kontenname+"</td>";
                html += "<td class=\"td_betrag\">"+line.saldo+"</td></tr>";
            }
            html += "</table>";
            html += "<br/><b>Ergebnis:</b><br/><table>";
            for(var key in data.ergebnisse) {
                var erg = data.ergebnisse[key];
                var bezeichnung;
                if(erg.kontenart_id === '3') bezeichnung = 'Aufwand';
                else if(erg.kontenart_id === '4') bezeichnung = 'Ertrag';
                else if(erg.kontenart_id === '5') bezeichnung = 'Saldo';
                html += "<tr><td>"+bezeichnung+"</td><td> "+erg.saldo+"</td></tr>";
            }
            html += "</table>";
            $("#ergebnis_form_guv_inner").html(html);
            $("#ergebnis_form_guv_years").selectmenu('refresh');
        }, 
        function(error) {
            alert(error);
        }
    );
},

loadGuVPrognose : function() {
    doGETwithCache("ergebnis", "guv_prognose", [],
        function(data) {
            html = "<b>GuV-Monatsvergleich</b><br/>";
            html += "<table>";

            html += "<tr><td>Konto</td><td></td>";
            html += "<td>Vormonat</td><td>Aktuell</td>";
            html += "<td>Differenz</td></tr>";

            for(var key in data.detail) {
                html += "<tr><td>"+data.detail[key].kontonummer+"</td>";
                html += "<td>"+data.detail[key].bezeichnung+"</td>";
                html += "<td class=\"td_betrag\">"+data.detail[key].betrag_vormonat+"</td>";
                html += "<td class=\"td_betrag\">"+data.detail[key].betrag_aktuell+"</td>";
                html += "<td class=\"td_betrag\">"+data.detail[key].differenz+"</td></tr>";
            }

            html += "<tr><td></td></tr>";
            html += "<tr><td colspan=\"4\"><b>Summen</b></td></tr>";
            
            for(var key in data.summen) {
                if(data.summen[key].kontenart_id === '5') {
                  html += "<tr><td>Saldo</td>";
                } else {
                  html += "<tr><td>"+data.summen[key].bezeichnung+"</td>";
                }
                html += "<td>"+data.summen[key].monat+"</td>";
                html += "<td class=\"td_betrag\">"+data.summen[key].saldo+"</td></tr>";
            }

            html += "</table>";
            $("#ergebnis_form_guv_prognose").html(html);
        },
        function(error) {
            alert(error);
        }
    );
},

loadGuVMonth : function() {
    // Combobox anzeigen
    $(".ergebnis_form_guv_controls").hide();
    $("#ergebnis_form_guv_controls_m").show();
    var selectedMonth = $("#ergebnis_form_guv_months").val();
    // Alte Anzeige (alte GuV-Inhalte) löschen
    var bitteWarten = "Bitte warten, die Ergebnisse werden geladen";
    $("#ergebnis_form_guv_inner").html(bitteWarten);
    doGETwithCache("ergebnis", "guv_month", {'id':selectedMonth}, 
        function(data) {
            var html = "<b>Gewinn und Verlust</b><br/> einzelner Monat:<br/><table>";
            for(var key in data.zeilen) {
                var line = data.zeilen[key];
                html += "<tr><td>"+line.konto+"</td><td>"+line.kontenname+"</td>";
                html += "<td class=\"td_betrag\">"+line.saldo+"</td></tr>";
            }
            html += "</table>";
            html += "<br/><b>Ergebnis:</b><br/><table>";
            for(var key in data.ergebnisse) {
                var erg = data.ergebnisse[key];
                var bezeichnung;
                if(erg.kontenart_id === '3') bezeichnung = 'Aufwand';
                else if(erg.kontenart_id === '4') bezeichnung = 'Ertrag';
                else if(erg.kontenart_id === '5') bezeichnung = 'Saldo';
                html += "<tr><td>"+bezeichnung+"</td><td> "+erg.saldo+"</td></tr>";
            }
            html += "</table>";
            $("#ergebnis_form_guv_inner").html(html);
            $("#ergebnis_form_guv_months").selectmenu('refresh');
        }, 
        function(error) {
            alert(error);
        }
    );
},

loadCacheFlow : function(konto) {
    $(".content_form").hide();
    $("#ergebnis_form_verlauf").show();
    $("#account_show_monatssalden").html("Cacheflow-Darstellung wird geladen");

    // Side muss auch noch dynamisch werden
    doGETwithCache('verlauf', 'cashflow', {'id':konto.kontonummer(), 'side': model.sollhaben()},
        function(data) {
            var table = "<table>";
            var type = "Mittelzufluss";
            if(model.sollhaben() === 'H') {
               type = "Mittelabfluss";
            }
            var code = "<b>"+type+": Konto: "+konto.kontonummer()+" "+konto.bezeichnung()+"</b><br/>";
            code += '<div id="ergebnis_show_monatssalden_table"></div>';
            code += '<canvas id="ergebnis_show_monatssalden_canvas" width="300px" height="300px"></canvas>';
            $("#ergebnis_form_verlauf").html(code);
            d.init("ergebnis_show_monatssalden_canvas");
            d.setToWindowWidth();
            var diagrammData = [];
            for(var key in data) {
                diagrammData.push(data[key].saldo);
                table += "<tr><td>"+data[key].grouping+"</td><td>"+data[key].saldo+"</td></tr>";
            }
            table += "</table>";
            $("#ergebnis_show_monatssalden_table").html(table);
            d.drawLineDiagramFor(diagrammData);
        }, 
        function(error) {
            $("#account_show_monatssalden").html("Es ist ein Fehler aufgetreten, die Daten konnten nicht geladen werden");
            console.log(error);
        }
    );
},

loadVerlauf : function(controller, action, parameter, kontenart_txt) {
    $("#account_show_monatssalden").html("Monatssalden werden geladen");
    
    doGETwithCache(controller, action, parameter,
        function(data) {
            var table = "<table>";
            var code = "<b>Verlauf in Monaten: Kontenart: "+kontenart_txt+"</b><br/>";
            code += '<div id="ergebnis_show_monatssalden_table"></div>';
            code += '<canvas id="ergebnis_show_monatssalden_canvas" width="300px" height="300px"></canvas>';
            $("#ergebnis_form_verlauf").html(code);
            d.init("ergebnis_show_monatssalden_canvas");
            d.setToWindowWidth();
            var diagrammData = [];
            for(var key in data) {
                diagrammData.push(data[key].saldo);
                table += "<tr><td>"+data[key].grouping+"</td><td class=\"td_betrag\">"+data[key].saldo+"</td></tr>";
            }
            table += "</table>";
            $("#ergebnis_show_monatssalden_table").html(table);
            d.drawLineDiagramFor(diagrammData);
        }, 
        function(error) {
            $("#account_show_monatssalden").html("Es ist ein Fehler aufgetreten, die Daten konnten nicht geladen werden");
            console.log(error);
        }
    );
},

loadVerlaufIntraMonth : function() {
    $("#ergebnis_form_verlauf_intra_inner").html("Monatssalden werden geladen");

    $("#ergebnis_form_intra_months").selectmenu('refresh');
    var month_id = $("#ergebnis_form_intra_months").val(); 
  
    doGETwithCache("verlauf", "intramonth", {'month_id': month_id},
        function(data) {
            var table = '<table width="100%" style="max-width:400px">';
            var code = "<b>Verlauf innerhalb des Monats:</b><br/>";
            code += '<canvas id="ergebnis_show_intramonth_canvas" width="300px" height="300px"></canvas>';
            code += '<div id="ergebnis_show_intramonth_table"></div>';
            $("#ergebnis_form_verlauf_intra_inner").html(code);
            d.init("ergebnis_show_intramonth_canvas");
            d.setToWindowWidth();
            var diagrammData = [[], [], []];
            table += '<tr><td width="25%">Tag</td><td width="25%">Aufwand</td>';
            table += '<td width="25%">Ertrag</td><td width="25%">Gewinn</td></tr>';
            for(var key in data) {
                diagrammData[0].push(data[key].aufwand);
                diagrammData[1].push(data[key].ertrag);
                diagrammData[2].push(data[key].gewinn);
                table += "<tr><td>"+data[key].day+"</td>";
                table += "<td class=\"td_betrag\">"+data[key].aufwand+"</td>";
                table += "<td class=\"td_betrag\">"+data[key].ertrag+"</td>";
                table += "<td class=\"td_betrag\">"+data[key].gewinn+"</td></tr>";
            }
            table += "</table>";
            $("#ergebnis_show_intramonth_table").html(table);
            d.drawMultiLineDiagramFor(diagrammData);
        }, 
        function(error) {
            $("#account_show_monatssalden").html("Es ist ein Fehler aufgetreten, die Daten konnten nicht geladen werden");
            console.log(error);
        }
    );
},

loadVerlaufKontenart : function(kontenart_id) {
    var kontenart_txt = '';
    var action = 'verlauf';
    if(kontenart_id === 4) kontenart_txt = 'Ertrag';
    if(kontenart_id === 3) kontenart_txt = 'Aufwand';
    if(kontenart_id === -1) {
        kontenart_txt = 'Gewinn';
        action = 'verlauf_gewinn';
    }
    ergebnisForm.loadVerlauf('ergebnis', action, {'id':kontenart_id}, kontenart_txt);
},

loadVerlaufFrei : function() {
    var kontoNumbers = "";
    var length = model.konten_selected().length;
    for(var i = 0; i < length; i++) {
        kontoNumbers += model.konten_selected()[i].kontonummer();
        if(i < length-1) {
            kontoNumbers += ",";
        }
    }
    //alert(kontoNumbers);
    ergebnisForm.loadVerlauf('verlauf', 'monatssalden', {'id':kontoNumbers}, 'Konten:'+kontoNumbers); 
},

exportJournal : function () {
    var win = window.open("../index.php?controller=office&action=journal&format=csv", "Download");
},

exportGuvMonate : function () {
    var win = window.open("../index.php?controller=office&action=guvmonate&format=csv", "Download");
}

};
</script>
