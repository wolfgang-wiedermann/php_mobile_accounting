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

// Eintrag im Schnellbuchungsmenü
hhb.model.types.Schnellbuchung = function(data) {
    var self = this;

    self.config_id = ko.observable(0);
    self.config_knz = ko.observable('');
    self.buchungstext = ko.observable('');
    self.sollkonto = ko.observable('0000');
    self.habenkonto = ko.observable('0000');
    self.betrag = ko.observable(0.00);
    self.mandant_id = ko.observable(0);

    if(!!data) {
        self.config_id(data.config_id);
        self.config_knz(data.config_knz);
        self.buchungstext(data.buchungstext);
        self.sollkonto(data.sollkonto);
        self.habenkonto(data.habenkonto);
        self.betrag(data.betrag);
        self.mandant_id(data.mandant_id);
    }
};

// Schnellbuchungen-Model
hhb.model.types.SchnellbuchungModel = function() {
    var self = this;

    self.schnellbuchungen = ko.observableArray([]);
    self.schnellbuchungen.push(new hhb.model.types.Schnellbuchung());
    self.schnellbuchungen_navigation = ko.observableArray([]);
    self.selectedSchnellbuchung = ko.observable(new hhb.model.types.NavigationEntry({'label':''}));

    // Schnellbuchung nach Klick ausführen
    self.executeSchnellbuchung = function(entry) {
        var selectedBuchung = hhb.model.MainModel.buchen().selectedBuchung();
        selectedBuchung.buchungstext(entry.buchungstext());
        selectedBuchung.sollkonto(entry.sollkonto());
        selectedBuchung.habenkonto(entry.habenkonto());
        selectedBuchung.betrag(entry.betrag());
    };

    // Laden der Liste der Schnellbuchungen
    self.load = function() {
        self.schnellbuchungen.removeAll();
        doGETwithCache("menu", "quick", [],
            function(data) {
                for(var i = 0; i < data.length; i++) {
                    var entry = new hhb.model.types.Schnellbuchung(data[i]);
                    self.schnellbuchungen.push(entry);
                    self.schnellbuchungen_navigation.push(new hhb.model.types.NavigationEntry({
                        label:data[i].config_knz,
                        target:'#buchungen_erfassen',
                        data:entry,
                        callback: function(data) {
                            self.executeSchnellbuchung(data.data);
                            jQuery.mobile.changePage(data.target());
                        }
                    }));
                }
                $("#h_navigation").listview('refresh');
            },
            function(error) {
                // TODO: Fehlermeldung ausgeben
            }
        );
    };

    self.load();
};