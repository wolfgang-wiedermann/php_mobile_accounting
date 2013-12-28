<?php defined("MAIN_PAGE") or die("Fehlende Berechtigung, Seite darf nur aus index.php geladen werden"); ?>
        <div id="navigation" class="content_form">
        <ul id="navigation_menu_listview" 
            data-role="listview" data-filter="true" data-filter-placeholder="Search Item..." data-theme="a">
            <li data-role="list-divider">Basisfunktionen</li>
            <li><a href="#" id="menu_buchen">Buchen</a></li>
            <li><a href="#" id="menu_konten">Konten</a></li>
            <li><a href="#" id="menu_auswerten">Auswertungen</a></li>
            <li data-role="list-divider">Administration</li>
            <li><a href="#" id="menu_admin_quick">Schnellbuchungen verw.</a></li>
            <!--
            <li><a href="#" id="admin_reports">Auswertungen verw.</a></li>
             -->
            <li data-role="list-divider" id="menu_schnellbuchungen_divider">Schnellbuchungen</li>
            <li class="menu_quick_item"><a href="#" class="menu_quick_item">Tanken</a></li>
        </ul>
        </div>
<script type="text/javascript">
/*
* Javascript-Code für das Schnellbuchungs-Menü
*/
var menu = {
    /*
    * Laden des Schnellbuchungs-Menüs
    */
    loadQuickMenuItems: function () {
        doGETwithCache("menu", "quick", [], function(data) {
            // Alte menu_quick_items entfernen, neue laden
            $(".menu_quick_item").remove();
            var listview = $("#navigation_menu_listview");
            for(var key in data) {
                var type = data[key];
                listview.append('<li class="menu_quick_item">'
                               +'<a href="#" class="menu_quick_item_a" data-key="'+type.config_id+'">'
                               +type.config_knz+'</a></li>');
            }
            listview.listview("refresh");
            menu.registerQuickMenuEvents();
        },
        function(error) {
            alert('Fehler beim laden des Schnellbuchungs-Menues');
        });
    },

    /*
    * Privat: Methode zum Registrieren der Ereignisse im 
    *         geladenen Schnellbuchungsmenü
    */
    registerQuickMenuEvents: function() {
        $(".menu_quick_item_a").unbind("click");
        $(".menu_quick_item_a").click(menu.handleQuickMenuEvent);
    },

    /*
    * Generischer Event-Handler für die Items des Schnell-
    * buchungs-Menüs. Bestimmt, aufgrund des Wertes des
    * Attributs data-key, welcher Menü-Eintrag ausgewählt
    * wurde und lädt die entsprechende Vorlage in die
    * Buchungsmaske. 
    */
    handleQuickMenuEvent: function(event) {
        var id = this.getAttribute("data-key");
        // Template-Inhalt laden
        doGETwithCache("menu", "get", {'id': id}, function(data) {
            menu.loadQuickNodeTemplate(data, model);
            buchungenForm.showErfassen();
            $("#header_home_button").show();
            $("#buchung_form_erfassung_betrag").focus();
        }, function(error) {
            alert("Fehler beim Laden des Templates aufgetreten");
        });
    },

    /*
    * Privat: Laden eines konkreten Templates in das Knockout.js-Model
    *         der Buchungsmaske.
    */
    loadQuickNodeTemplate: function(data, viewModel) {
       viewModel.buchung().buchungstext(data.buchungstext);
       viewModel.buchung().sollkonto(data.sollkonto);
       viewModel.buchung().habenkonto(data.habenkonto);
       if(!!data.betrag) { // Wenn der Betrag nicht null ist
           viewModel.buchung().betrag(data.betrag);
       }
       viewModel.buchung().datum(JSON.stringify(new Date()).substring(1,11));

       $("#buchung_form_erfassung_sollkonto").selectmenu("refresh", true);
       $("#buchung_form_erfassung_habenkonto").selectmenu("refresh", true);
   },
};

/* 
* Schnellbuchungsmenue nach dem Laden der Applikation
* automatisch laden und 
* die Event-Handler fuer die Eintraege registrieren
*/
$(document).ready(function() {
    menu.loadQuickMenuItems();
});
</script>
