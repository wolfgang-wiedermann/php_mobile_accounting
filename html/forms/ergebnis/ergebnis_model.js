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

    self.titel = ko.observable("Ergebnisrechnung");
    self.untertitel = ko.observable("Name der Ergebnisrechnung");

    self.rechnung = ko.observableArray([]);
    self.rechnung.push(new hhb.model.types.ErgebnisRechnungEintrag());

    self.summen = ko.observableArray([]);
    self.summen.push(new hhb.model.types.ErgebnisRechnungSumme());

    // Funktionen zum Laden der Daten ...
    self.bilanz = function() {
        self.rechnung.removeAll();
        self.summen.removeAll();

        self.titel("Bilanz");
        self.untertitel("Vermögen und Kapital");

        doGETwithCache("ergebnis", "bilanz", [],
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
                    else if (line.kontenart_id === '5') bezeichnung = 'Saldo';

                    var item = new hhb.model.types.ErgebnisRechnungSumme();
                    item.label(bezeichnung);
                    item.betrag(line.saldo);

                    self.summen.push(item);
                }

                jQuery.mobile.changePage('#ergebnis_view');
            },
            function (error) {
                util.showErrorMessage(error, 'Fehler beim Laden der Bilanz aufgetreten');
            }
        );
    };
};