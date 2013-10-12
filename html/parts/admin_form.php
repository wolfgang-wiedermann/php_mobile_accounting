<?php defined("MAIN_PAGE") or die("Fehlende Berechtigung, Seite darf nur aus index.php geladen werden"); ?>
<!-- Auswahl der Quick-Eintraege zur Administration -->
<div id="admin_quick_select_view" class="content_form">
<ul data-bind="foreach: quickentries"
    data-role="listview" data-filter="true" data-filter-placeholder="Search Item..." data-theme="d">
<li><a href="#" data-bind="text: config_knz, attr: {'data-key': config_id}, click: $root.onQuickClick"></a></li>
</ul>
</div>
<!-- Formular zur Bearbeitung der Quick-Eintraege -->
<div id="admin_quick_form_view" class="content_form">
<label for="admin_quick_form_config_knz">Kurzbezeichnung</label>
<input id="admin_quick_form_config_knz" data-bind="value: $root.selectedquick.config_knz"></input>
<label for="admin_quick_form_buchungstext">Buchungstext</label>
<input id="admin_quick_form_buchungstext" data-bind="value: $root.selectedquick.buchungstext"></input>
<label for="admin_quick_form_sollkonto">Soll-Konto</label>
<input id="admin_quick_form_sollkonto" data-bind="value: $root.selectedquick.sollkonto"></input>
<label for="admin_quick_form_habenkonto">Haben-Konto</label>
<input id="admin_quick_form_habenkonto" data-bind="value: $root.selectedquick.habenkonto"></input>
<button data-bind="click: $root.saveSelectedQuick">Speichern</button>
<button data-bind="click: $root.deleteSelectedQuick">L&ouml;schen</button>
</div>
<script type="text/javascript">
var adminForm = {
    /*
    * Formular zur Bearbeitung der Quick-Eintraege laden
    */
    loadAdminQuickFormView : function(quick) {
        $(".content_form").hide();
        $("#admin_quick_form_view").show();
    },

    /*
    * Eintrag model.selectedquick speichern
    */
    saveAdminQuickTemplate : function(quick) {
        var jsonQuick = ko.toJSON(quick);
        //alert(jsonQuick);
        doPOST("menu", "add", jsonQuick, function(data) {
                $(".content_form").hide();
                model.refreshQuick();
                $("#admin_quick_select_view").show();
            },
            function(error) {
                alert("Fehler beim Speichern aufgetreten");
            }
        ); 
    },

    /*
    * Eintrag model.selectedquick löschen
    */
    deleteAdminQuickTemplate : function(quick) {
        var id = quick.config_id();
        doGET("menu", "remove", {'id': id}, 
            function(data) {
                $(".content_form").hide();
                model.refreshQuick();
                $("#admin_quick_select_view").show();
            },
            function(error) {
            }
        );
    },
}
</script>
