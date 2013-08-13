<?php defined("MAIN_PAGE") or die("Fehlende Berechtigung, Seite darf nur aus index.php geladen werden"); ?>
        <div id="navigation" class="content_form">
        <ul id="navigation_menu_listview" 
            data-role="listview" data-filter="true" data-filter-placeholder="Search Item..." data-theme="d">
            <li data-role="list-divider">Basisfunktionen</li>
            <li><a href="#" id="menu_buchen">Buchen</a></li>
            <li><a href="#" id="menu_konten">Konten</a></li>
            <li><a href="#" id="menu_auswerten">Auswertungen</a></li>
            <li data-role="list-divider">Schnellbuchungen</li>
            <li class="menu_quick_item"><a href="#" class="menu_quick_item">Tanken</a></li>
<!--
            <li data-role="list-divider">Administration</li>
            <li><a href="#" id="menu_config">Einstellungen</a></li>
-->
        </ul>
        </div>
<script type="text/javascript">
var menu = {
    loadQuickMenuItems: function () {
        doGET("menu", "quick", [], function(data) {
            // TODO: Alte menu_quick_items entfernen, neue laden
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

    registerQuickMenuEvents: function() {
        $(".menu_quick_item_a").unbind("click");
        $(".menu_quick_item_a").click(menu.handleQuickMenuEvent);
    },

    handleQuickMenuEvent: function(event) {
        var id = this.getAttribute("data-key");
        alert('Klick: '+id);
    },
};

// Schnellbuchungsmenue laden und Handler fuer die Eintraege registrieren
$(document).ready(function() {
    menu.loadQuickMenuItems();
});
</script>
