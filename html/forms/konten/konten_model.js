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
* Zusammenfassenden Model-Typen für den Themenbereich Buchungen 
*/
hhb.model.types.KontenModel = function() {
  var self = this;
  
  self.selectedKonto = ko.observable(new hhb.model.types.Konto());
  self.konten = ko.observableArray([]);

  // self.konten mit den auf dem Server vorgehaltenen Konten befüllen
  self.refreshKonten = function() {
    self.konten.removeAll();
    doGETwithCache("konto", "list", [], 
      function(data) {
        for(var i = 0; i < data.length; i++) {
          self.konten.push(new hhb.model.types.Konto(data[i]));
        }
      },
      function(error) {
        console.log(error);
        alert("Fehler aufgetreten, details siehe Log");
      }
    ); 
  };

  // Konten intial laden
  self.refreshKonten();
}
