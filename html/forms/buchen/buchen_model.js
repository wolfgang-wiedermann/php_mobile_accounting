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

/*
* Datenmodell eines Buchungseintrags
*/
hhb.model.types.Buchung = function(config) {
  var self = this;

  self.buchungstext = ko.observable("");
  self.buchungsnummer = ko.observable(0);
  self.sollkonto = ko.observable("0");
  self.habenkonto = ko.observable("0");
  self.betrag = ko.observable(0.00);
  self.datum = ko.observable(JSON.stringify(new Date()).substring(1,11));
  self.datum_de = ko.computed(function() {
    return util.formatDateAtG(self.datum());
  });
  self.benutzer = ko.observable("");

  if(!!config) {
    self.buchungstext(config.buchungstext);
    self.buchungsnummer(config.buchungsnummer);
    self.sollkonto(config.sollkonto);
    self.habenkonto(config.habenkonto);
    self.betrag(config.betrag);
    self.datum(config.datum);
    self.benutzer(config.benutzer);
  }
};

/*
* Zusammenfassenden Model-Typen für den Themenbereich Buchungen 
*/
hhb.model.types.BuchungenModel = function() {
  var self = this;
  
  self.selectedBuchung = ko.observable(new hhb.model.types.Buchung());
  self.buchungen = ko.observableArray([]);

  // Event-Handler für den Klick auf den Verbuchen-Button
  self.verbuchen = function() {
    var jsonString = ko.toJSON(self.selectedBuchung);
    doPOSTwithQueue("buchung", "create", jsonString,
      function(data) {
        alert("Buchung erfolgreich angelegt");
      },
      function(error) {
        alert("Fehler beim Anlegen der Buchung aufgetreten: "+JSON.stringify(error));
      }
    );
  };

  // Handler für das Laden der 25 aktuellsten Buchungen
  // in die Variable self.buchungen
  self.loadAktuellsteBuchungen = function() {
    self.buchungen.removeAll();
    doGETwithCache("buchung", "aktuellste", [], 
      function(data) {
        for(var i = 0; i < data.length; i++) {
          self.buchungen.push(new hhb.model.types.Buchung(data[i]));
        }
      }, 
      function(error) {
      }
    ); 
  };
}
