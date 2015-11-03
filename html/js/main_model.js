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
hhb.model.MainModel = null;

/*
* Root-Knoten des Knockout.js Models
*/
hhb.model.types.MainModel = function() {
  var self = this;
  // Modelbestandteile laden
  self.kontenarten = ko.observableArray([]);
  hhb.model.types.Kontenart.load(self.kontenarten);

  self.i18n = hhb.i18n;
  self.navigation = ko.observable(new hhb.model.types.NavigationModel());
  self.buchen = ko.observable(new hhb.model.types.BuchungenModel());
  self.konten = ko.observable(new hhb.model.types.KontenModel());
  self.schnellbuchungen = ko.observable(new hhb.model.types.SchnellbuchungModel());
  self.ergebnis = ko.observable(new hhb.model.types.ErgebnisModel());
  self.verlauf = ko.observable(new hhb.model.types.VerlaufModel());
  self.exporte = ko.observable(new hhb.model.types.ExportModel());
  self.configuration = ko.observable(new hhb.model.types.ConfigurationModel());

  self.ergebnis().initialize();
};

/*
* Knockout.js Model initialisieren und binden
*/
$(document).ready(function() {
  hhb.model.MainModel = new hhb.model.types.MainModel();
  ko.applyBindings(hhb.model.MainModel);
  jQuery.mobile.changePage("#");
});

/*
* Fade-In bei Seitenwechsel deaktivieren
*/
$(document).bind('pageinit', function () {
  $.mobile.defaultPageTransition = 'none';
});