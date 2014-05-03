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
<!-- Auswahl der Quick-Eintraege zur Administration -->
<div id="admin_quick_select_view" class="content_form">
<ul data-bind="foreach: quickentries, jqmRefreshList: quickentries"
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
<select id="admin_quick_form_sollkonto" data-bind="value: $root.selectedquick.sollkonto, options:konten, optionsText:'tostring', optionsValue:'kontonummer'"></select>
<label for="admin_quick_form_habenkonto">Haben-Konto</label>
<select id="admin_quick_form_habenkonto" data-bind="value: $root.selectedquick.habenkonto, options:konten, optionsText:'tostring', optionsValue:'kontonummer'"></select>
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
        $("#admin_quick_form_sollkonto").selectmenu("refresh", true);
        $("#admin_quick_form_habenkonto").selectmenu("refresh", true);
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
