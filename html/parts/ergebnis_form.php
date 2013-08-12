<?php defined("MAIN_PAGE") or die("Fehlende Berechtigung, Seite darf nur aus index.php geladen werden"); ?>
<!-- Funktionsauswahl - Buchungen -->
<div id="ergebnis_form" class="content_form">
<ul data-role="listview">
    <li><a href="#" id="ergebnis_action_bilanz" class="ergebnis_form_item">Bilanz</a></li>
    <li><a href="#" id="ergebnis_action_guv" class="ergebnis_form_item">Gewinn und Verlust</a></li>
    <li><a href="#" id="ergebnis_action_guv_month" class="ergebnis_form_item">GuV aktueller Monat</a></li>
</ul>
</div>
<!-- Bilanz -->
<div id="ergebnis_form_bilanz" class="content_form">
</div>
<!-- GuV -->
<div id="ergebnis_form_guv" class="content_form">
</div>
<!-- JavaScript-Code -->
<script type="text/javascript">
var ergebnisForm = {

registerErgebnisFormEvents : function() {
    $(".ergebnis_form_item").unbind("click");
    $("#ergebnis_action_bilanz").click(ergebnisForm.showBilanz);
    $("#ergebnis_action_guv").click(ergebnisForm.showGuV);
    $("#ergebnis_action_guv_month").click(ergebnisForm.showGuVMonth);
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

loadBilanz : function() {
    doGET("ergebnis", "bilanz", [], 
        function(data) {
            var html = "Bilanzdarstellung:<br/><table>";
            for(var key in data.zeilen) {
                var line = data.zeilen[key];
                html += "<tr><td>"+line.konto+"</td><td>"+line.kontenname+"</td><td>"+line.saldo+"</td></tr>";
            }
            html += "</table>";
            html += "<br/><b>Ergebnis:</b><br/><table>";
            for(var key in data.ergebnisse) {
                var erg = data.ergebnisse[key];
                var bezeichnung;
                if(erg.kontenart_id == '1') bezeichnung = 'Aktiva';
                else if(erg.kontenart_id == '2') bezeichnung = 'Passiva';
                else if(erg.kontenart_id == '5') bezeichnung = 'Saldo'; 
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
    doGET("ergebnis", "guv", [], 
        function(data) {
            var html = "Gewinn und Verlust:<br/><table>";
            for(var key in data.zeilen) {
                var line = data.zeilen[key];
                html += "<tr><td>"+line.konto+"</td><td>"+line.kontenname+"</td><td>"+line.saldo+"</td></tr>";
            }
            html += "</table>";
            html += "<br/><b>Ergebnis:</b><br/><table>";
            for(var key in data.ergebnisse) {
                var erg = data.ergebnisse[key];
                var bezeichnung;
                if(erg.kontenart_id == '3') bezeichnung = 'Aufwand';
                else if(erg.kontenart_id == '4') bezeichnung = 'Ertrag';
                else if(erg.kontenart_id == '5') bezeichnung = 'Saldo';
                html += "<tr><td>"+bezeichnung+"</td><td> "+erg.saldo+"</td></tr>";
            }
            html += "</table>";
            $("#ergebnis_form_guv").html(html);
        }, 
        function(error) {
            alert(error);
        }
    );
},

loadGuVMonth : function() {
    doGET("ergebnis", "guv_month", [], 
        function(data) {
            var html = "<b>Gewinn und Verlust</b><br/> aktueller Monat:<br/><table>";
            for(var key in data.zeilen) {
                var line = data.zeilen[key];
                html += "<tr><td>"+line.konto+"</td><td>"+line.kontenname+"</td><td>"+line.saldo+"</td></tr>";
            }
            html += "</table>";
            html += "<br/><b>Ergebnis:</b><br/><table>";
            for(var key in data.ergebnisse) {
                var erg = data.ergebnisse[key];
                var bezeichnung;
                if(erg.kontenart_id == '3') bezeichnung = 'Aufwand';
                else if(erg.kontenart_id == '4') bezeichnung = 'Ertrag';
                else if(erg.kontenart_id == '5') bezeichnung = 'Saldo';
                html += "<tr><td>"+bezeichnung+"</td><td> "+erg.saldo+"</td></tr>";
            }
            html += "</table>";
            $("#ergebnis_form_guv").html(html);
        }, 
        function(error) {
            alert(error);
        }
    );
},

};
</script>
