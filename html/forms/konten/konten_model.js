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
* Datenmodell eines Kontos
*/
hhb.model.types.Konto = function(config) {
  var self = this;

  self.kontonummer = ko.observable("0000");
  self.bezeichnung = ko.observable("");
  self.kontenart_id = ko.observable(0);
  self.mandant_id = ko.observable(0);

  self.tostring = ko.computed(function() {
    return self.kontonummer()+" : "+self.bezeichnung();
  });

  if(!!config) {
    self.kontonummer(config.kontonummer);
    self.bezeichnung(config.bezeichnung);
    self.kontenart_id(config.kontenart_id);
    self.mandant_id(config.mandant_id);
  }
};

/*
* Zusammenfassenden Model-Typen für den Themenbereich Konten
*/
hhb.model.types.KontenModel = function() {
  var self = this;
  
  self.selectedKonto = ko.observable(new hhb.model.types.Konto());
  self.konten = ko.observableArray([]);
  self.konten.push(self.selectedKonto);
  self.buchungen = ko.observableArray([]);
  self.buchungen.push(new hhb.model.types.KontoBuchung());
  self.saldo = ko.observable("");

  // self.konten mit den auf dem Server vorgehaltenen Konten befüllen
  self.refreshKonten = function() {
    self.konten.removeAll();
    doGETwithCache("konto", "list", [], 
      function(data) {
        for(var i = 0; i < data.length; i++) {
          self.konten.push(new hhb.model.types.Konto(data[i]));
        }
        $(".konten_liste").listview();
        $(".konten_liste").listview("refresh");
      },
      function(error) {
        console.log(error);
        alert("Fehler aufgetreten, details siehe Log");
      }
    ); 
  };

  // Menü nach der Auswahl eines Kontos öffnen
  self.openKontenMenu = function(item) {
    self.selectedKonto(item);
    jQuery.mobile.changePage("#konten_menue");
  };

  // Formular Konto bearbeiten öffnen
  self.openKontenBearbeiten = function() {
    jQuery.mobile.changePage("#konto_bearbeiten");
    $('#konto_bearbeiten').trigger('create');
  };

  // Buchungen des aktuell selektierten Kontos in #konto_buchungen anzeigen
  self.openBuchungen = function() {
    self.buchungen.removeAll();
    var kontonummer = self.selectedKonto().kontonummer();
    doGETwithCache("buchung", "listbykonto", {'konto':kontonummer},
        function(data) {
          var list = data.list;
          self.saldo(data.saldo);
          for(var i = 0; i < list.length; i++) {
            self.buchungen.push(new hhb.model.types.KontoBuchung(list[i]));
          }
          jQuery.mobile.changePage("#konto_buchungen");
        },
        function(error) {
          // TODO: Fehlermeldung ausgeben!
        }
    );
  };

  // Neues Konto anlegen
  self.anlegen = function() {
    var kontoJSON = ko.toJSON(self.selectedKonto());
    doPOST("konto", "create", kontoJSON,
        function(data) {
          alert('Das Konto wurde angelegt');
        },
        function(error) {
          // TODO: Fehlerbehandlung noch verallgemeinern
          console.log(error);
          alert("Es ist ein Fehler beim Anlegen des Kontos aufgetreten");
        }
    );
  };

  // Bestehendes Konto aktualisieren
  self.speichern = function() {
    var kontoJSON = ko.toJSON(self.selectedKonto());
    doPOST("konto", "save", kontoJSON,
        function(data) {
          alert('Die Änderungen wurden gespeichert');
        },
        function(error) {
          // TODO: Fehlerbehandlung noch verallgemeinern
          console.log(error);
          alert("Es ist ein Fehler beim Speichern des Kontos aufgetreten");
        }
    );
  };

  // Konten intial laden
  self.refreshKonten();
}
