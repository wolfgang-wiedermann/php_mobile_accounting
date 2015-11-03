// -- <?php
/*
 * Copyright (c) 2015 by Wolfgang Wiedermann
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
//?>

var hhb = hhb || {};
hhb.model = hhb.model || {};
hhb.model.types = hhb.model.types || {};

hhb.model.types.ConfigurationParam = function(data) {
    var self = this;
    self.param_id = ko.observable();
    self.param_knz = ko.observable("");
    self.param_desc = ko.observable("");
    self.param_value = ko.observable("");

    if(!!data) {
        self.param_id(data.param_id);
        self.param_knz(data.param_knz);
        self.param_desc(data.param_desc);
        self.param_value(data.param_value);
    }

    self.description = ko.computed(function() {
       return self.param_knz()+" : "+self.param_desc();
    });
};

hhb.model.types.ConfigurationModel = function(data) {
    var self =  this;
    self.selectedConfigurationParam = ko.observable(new hhb.model.types.ConfigurationParam());
    self.configuration = ko.observableArray([]);

    // Laden der Liste der Konfigurationsparameter
    self.load = function() {
        doGETwithCache("config", "list", [],
            function(data) {
                var array = [];
                for(var i = 0; i < data.length; i++) {
                    array.push(new hhb.model.types.ConfigurationParam(data[i]));
                }
                self.configuration(array);

                // JQuery-Mobile Listview aktualisieren
                $("#configuration_liste ul").listview();
                $("#configuration_liste ul").listview('refresh');
            },
            function(error) {
                util.showErrorMessage(error, "Fehler beim Laden der Konfiguration aufgetreten");
            }
        );
    }

    // Übertragen von selectedConfigurationParam an den Server zur aktualisierung
    // der gespeicherten Version
    self.updateConfigurationParam = function() {
        var selected = self.selectedConfigurationParam();
        var selectedJSON = ko.toJSON(selected);
        doPOST("config", "update", selectedJSON,
            function(data) {
                alert('Parameter aktualisiert');
                jQuery.mobile.changePage("#configuration_liste");
            },
            function(error) {
                util.showErrorMessage(error, "Fehler beim Speichern der Schnellbuchung aufgetreten");
            }
        );
    }

    // Öffnen eines einzelnen Konfigurationsparameters in der
    // Bearbeiten-Ansicht.
    self.open = function(selected) {
        self.selectedConfigurationParam(selected);
        jQuery.mobile.changePage("#configuration_form");
    }
};