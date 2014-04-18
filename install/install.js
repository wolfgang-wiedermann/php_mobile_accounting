/*
 * Copyright (c) 2014 by Wolfgang Wiedermann
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

// Knockout-Model für den Installer
function InstallerModel() {
  var self = this;
  self.database = new DatabaseModel();
  self.user = new UserModel();

  // TODO: hier noch die Aktionen Auflisten, die ich auf data-bind:click registriere!
}

// Model für die Datenbank-Verbindungsdaten
function DatabaseModel() {
  var self = this;
  self.hostname = ko.observable("localhost");
  self.port = ko.observable("3306");
  self.database = ko.observable("fibu");
  self.username = ko.observable("");
  self.password = ko.observable("");

  // TODO: Methode für onTestConnection

  // TODO: Methode für storeConnectionSettings
}

// Model für die erfassten Haushaltsbuch-Benutzer
function UserModel() {
  var self = this;
  self.username = ko.observable("");
  self.password = ko.observable("");

  // TODO: Methode für createUser
}

// Model initialisieren und in Knockout.js laden
var model = new InstallerModel();
$(document).ready(function() {
  ko.applyBindings(model);
});
