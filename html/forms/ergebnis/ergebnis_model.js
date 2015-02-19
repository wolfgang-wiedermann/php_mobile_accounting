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

// Eintrag der Ergebnisrechnungen Bilanz, GuV und GuV-Monat
hhb.model.types.ErgebnisRechnungEintrag = function(data) {
    var self = this;

    self.kontonummer = ko.observable("");
    self.bezeichnung = ko.observable("");
    self.betrag = ko.observable("0,00");

    if(!!data) {
        self.kontonummer(data.kontonummer);
        self.bezeichnung(data.bezeichnung);
        self.betrag(data.betrag);
    }
};

// Eintrag in den Summenbereich der Ergebnisrechnungen Bilanz, GuV und GuV-Monat
hhb.model.types.ErgebnisRechnungSumme = function(data) {
    var self = this;

    self.label = ko.observable("");
    self.betrag = ko.observable("0,00");

    if(!!data) {
        self.label(data.label);
        self.betrag(data.betrag);
    }
};

// Model für Ergebnisrechnungen
hhb.model.types.ErgebnisModel = function() {
    var self = this;
    var priv = {};

    self.titel = ko.observable("Ergebnisrechnung");
    self.untertitel = ko.observable("Name der Ergebnisrechnung");
    self.selected_monat = ko.observable('201502');
    self.selected_jahr = ko.observable('2015');
    self.monat_selection_visible = ko.observable(false);
    self.jahr_selection_visible = ko.observable(false);

    self.jahre = ko.observableArray([]);
    self.monate = ko.observableArray([]);

    self.rechnung = ko.observableArray([]);
    self.rechnung.push(new hhb.model.types.ErgebnisRechnungEintrag());

    self.summen = ko.observableArray([]);
    self.summen.push(new hhb.model.types.ErgebnisRechnungSumme());

    // Liste der auswählbaren Monate aktualisieren
    self.updateMonate = function(successHandler) {
        doGETwithCache("ergebnis", "months", [],
            function(data) {
                self.monate.removeAll();
                for(var i = 0; i < data.length; i++) {
                    if(i+1 === data.length) {
                        self.monate.push({'monat':data[i], 'selected':true});
                        self.selected_monat(data[i]);
                    } else {
                        self.monate.push({'monat':data[i], 'selected':false});
                    }
                }

                if(!!successHandler) {
                    successHandler();
                }
            },
            function(error) {
                alert("Fehler aufgetreten: "+JSON.stringify(error));
            }
        );
    };

    // Liste der auswählbaren Jahre aktualisieren
    self.updateJahre = function(successHandler) {
        doGETwithCache("ergebnis", "years", [],
            function(data) {
                self.jahre.removeAll();
                for(var i = 0; i < data.length; i++) {
                    if(i+1 === data.length) {
                        self.jahre.push({'jahr':data[i], 'selected':true});
                        self.selected_jahr(data[i]);
                    } else {
                        self.jahre.push({'jahr':data[i], 'selected':false});
                    }
                }

                if(!!successHandler) {
                    successHandler();
                }
            },
            function(error) {
                alert("Fehler aufgetreten: "+JSON.stringify(error));
            });
    };

    // Jahre und Monate laden (wird von MainModel aufgerufen!)
    self.initialize = function() {
        self.updateMonate();
        self.updateJahre();
    };

    // Dummy-Funktion die jeweils durch self.bilanz, self.guvjahr etc. ersetzt wird
    self.onchange = function() {};

    // Funktionen zum Laden der Daten Bilanz
    self.bilanz = function() {
        self.jahr_selection_visible(false);
        self.monat_selection_visible(false);
        priv.loadErgebnisrechnung("bilanz", "Bilanz", "Vermögen und Kapital", []);
    };

    // Funktion zum Laden der Daten der GuV-Rechnung nach Jahren
    self.guvjahr = function() {
        self.jahr_selection_visible(true);
        self.monat_selection_visible(false);
        self.onchange = self.guvjahr;
        priv.loadErgebnisrechnung("guv", "Gewinn und Verlust", "Aufwand und Ertrag pro Jahr",
                                  {'year':self.selected_jahr()});
    };

    // Funktion zum Laden der Daten der GuV-Rechnung nach Monaten
    self.guvmonat = function() {
        self.jahr_selection_visible(false);
        self.monat_selection_visible(true);
        self.onchange = self.guvmonat;
        priv.loadErgebnisrechnung("guv_month", "Gewinn und Verlust", "Aufwand und Ertrag pro Monat",
                                  {'id':self.selected_monat()});
    };



    // Allgemeine Funktion zum Laden von Bilanz und GuV-Rechnungen
    priv.loadErgebnisrechnung = function(action, titel, untertitel, parameters) {
        var parameters = parameters || [];

        self.rechnung.removeAll();
        self.summen.removeAll();

        self.titel(titel);
        self.untertitel(untertitel);

        doGETwithCache("ergebnis", action, parameters,
            function (data) {
                for (var key in data.zeilen) {
                    var line = data.zeilen[key];
                    var item = new hhb.model.types.ErgebnisRechnungEintrag();

                    item.kontonummer(line.konto);
                    item.bezeichnung(line.kontenname);
                    item.betrag(line.saldo);

                    self.rechnung.push(item);
                }

                for (var key in data.ergebnisse) {
                    var line = data.ergebnisse[key];
                    var bezeichnung = '';
                    if (line.kontenart_id === '1') bezeichnung = 'Aktiva';
                    else if (line.kontenart_id === '2') bezeichnung = 'Passiva';
                    else if (line.kontenart_id === '3') bezeichnung = 'Aufwand';
                    else if (line.kontenart_id === '4') bezeichnung = 'Ertrag';
                    else if (line.kontenart_id === '5') bezeichnung = 'Saldo';

                    var item = new hhb.model.types.ErgebnisRechnungSumme();
                    item.label(bezeichnung);
                    item.betrag(line.saldo);

                    self.summen.push(item);
                }

                jQuery.mobile.changePage('#ergebnis_view');
            },
            function (error) {
                util.showErrorMessage(error, 'Fehler beim Laden der '+titel+' aufgetreten');
            }
        );
    };
};