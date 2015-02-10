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
};

// Model für Ergebnisrechnungen
hhb.model.types.ErgebnisModel = function() {
    var self = this;

    self.titel = ko.observable("Ergebnisrechnung");
    self.rechnung = ko.observableArray([]);
    self.rechnung.push(new hhb.model.types.ErgebnisRechnungEintrag());

    // Funktionen zum Laden der Daten ...
};