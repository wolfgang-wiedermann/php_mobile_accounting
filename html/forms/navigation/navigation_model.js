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
* Datenmodell eines Navigationseintrags
* Config-Parameter:
* label    -> Beschreibungsstring
* target   -> Navigationsziel
* callback -> Callback, der beim Klick auf den Eintrag aufgerufen werden soll
*/
hhb.model.types.NavigationEntry = function(config) {
  var self = this;
  self.label = ko.observable(config.label);
  self.target = ko.observable(config.target);
  self.callback = config.callback;
  if(!!config.data) {
    self.data = config.data;
  }
};

/*
* Datenmodell der Haupt-Navigation des Haushaltsbuchs
*/
hhb.model.types.NavigationModel = function() {
  var self = this;

  // Basisfunktionen - Menü
  self.basisfunktionen = ko.observableArray([]);

  self.basisfunktionen.push(new hhb.model.types.NavigationEntry({
    label:hhb.i18n.navigation.buchen, // Buchen
    target:'#buchen_menue',
    callback: function(data) {
      console.log(data.label());
      jQuery.mobile.changePage(data.target());
    }
  }));

  self.basisfunktionen.push(new hhb.model.types.NavigationEntry({
    label:hhb.i18n.navigation.konten, // Konten
    target:'#konten_liste',
    callback: function(data) {
      hhb.model.MainModel.konten().refreshKonten();
      console.log(data.label());
      jQuery.mobile.changePage(data.target());
    }
  }));

  self.basisfunktionen.push(new hhb.model.types.NavigationEntry({
    label:hhb.i18n.navigation.auswertungen, // Auswertungen
    target:'#ergebnis_menue',
    callback: function(data) {
      console.log(data.label());
      jQuery.mobile.changePage(data.target());
    }
  }));

  // Administration - Menü
  self.administration = ko.observableArray([]);

  self.administration.push(new hhb.model.types.NavigationEntry({
    label:hhb.i18n.navigation.schnellbuchungen, // Schnellbuchungen verwalten
    target:'#schnellbuchungen_liste',
    callback: function(data) {
      console.log(data.label());
      jQuery.mobile.changePage(data.target());
    }
  }));

  self.administration.push(new hhb.model.types.NavigationEntry({
    label:hhb.i18n.navigation.configuration, // Konfigurationsparameter verwalten
    target:'#configuration_liste',
    callback: function(data) {
      hhb.model.MainModel.configuration().load();
      console.log(data.label());
      jQuery.mobile.changePage(data.target());
    }
  }));

  // Buchungsmenü (Untermenü in/für Formular buchen_menue.php)
  self.buchungsmenue = ko.observableArray([]);

  self.buchungsmenue.push(new hhb.model.types.NavigationEntry({
    label:hhb.i18n.navigation.buchung_erfassen, // Buchung erfassen
    target:'#buchungen_erfassen',
    callback: function(data) {
      hhb.model.MainModel.konten().refreshKonten()
      console.log(data.label());
      jQuery.mobile.changePage(data.target());

      // jQuery-Mobile Selectboxen neu laden
      $("#b_sollkonto").selectmenu();
      $("#b_habenkonto").selectmenu();
      $("#b_sollkonto").selectmenu("refresh", true);
      $("#b_habenkonto").selectmenu("refresh", true);
    }
  }));

  self.buchungsmenue.push(new hhb.model.types.NavigationEntry({
    label:hhb.i18n.buchen.aktuellste_buchungen, // Aktuellste Buchungen
    target:'#aktuellste_buchungen',
    callback: function(data) {
      hhb.model.MainModel.buchen().loadAktuellsteBuchungen();
      console.log(data.label());
      jQuery.mobile.changePage(data.target());
    }
  }));

  self.buchungsmenue.push(new hhb.model.types.NavigationEntry({
    label:hhb.i18n.buchen.offene_posten, // Offene Posten
    target:'#offene_posten',
    callback: function(data) {
      hhb.model.MainModel.buchen().getOffenePosten();
      console.log(data.label());
      jQuery.mobile.changePage(data.target());
    }
  }));

  self.buchungsmenue.push(new hhb.model.types.NavigationEntry({
    label:hhb.i18n.buchen.warteschlange, // Buchungs-Warteschlange
    target:'#buchungen_warteschlange',
    callback: function(data) {
      hhb.model.MainModel.buchen().getWarteschlange();
      console.log(data.label());
      jQuery.mobile.changePage(data.target());
    }
  }));

};
