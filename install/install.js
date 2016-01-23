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

/**
* Funktion zum Absetzen eines POST-Requests
* @param controller = Bezeichnung des Controllers als String (wie in URL)
* @param action = Bezeichnung der Action als String
* @param parameterObject = Parameter als JSON-Objekt (String)
* @param successHandler = Funktions-Handle für Erfolgsfall
* @param errorHandler = Funktions-Handle für Fehlerfall
*/
function doPOST(controller, action, parameterObject, successHandler, errorHandler) {
    $.ajax({
        type: 'POST',
        url: "./install/rpc.php?controller="+controller+"&action="+action,
        dataType:"json",
        contentType:"application/json",
        data: parameterObject,
    }).done(function(data) {
        successHandler(data);
    }).fail(function(error) {
        errorHandler(error);
    });
}

// Knockout-Model für den Installer
function InstallerModel() {
  var self = this;
  self.analysis = new AnalysisModel();
  self.database = new DatabaseModel();
  self.user = new UserModel();

  // Systemanalyse starten
  self.onCheckSystem = function() {
    doPOST("installation", "checksystem", "",
      function (data) {
        if(!data.isError) {
           var messages = [];
           for(var i = 0; i < data.length; i++) {
               messages.push({"description":data[i]});
           }
           self.analysis.results(messages);
        }
      },
      function (error) {
        alert("Fehler aufgetreten: "+error.statusText);
      }
    );
  };

  // hier noch die Aktionen Auflisten, die ich auf data-bind:click registriere!
  self.onCreateDbSchema = function() {
    doPOST("installation", "createdbschema", "",
      function (data) {
        if(data.isError) {
          alert("Fehler aufgetreten: "+data.message);
        } else {
          alert("Erfolg: "+data.message);
          $.mobile.navigate("#user_config");
        }
      },
      function (error) {
        alert("Fehler aufgetreten: "+error.statusText);
      }
    );
  };

  // .htaccess-Datei scharf schalten
  self.onActivateHtaccess = function() {
    doPOST("installation", "sethtaccess", "",
      function (data) {
        if(data.isError) {
           // Fehler im Dialog anzeigen
           $("#fehler_ausgeben_meldung").html("<h2>"+data.message
                +"</h2><p>Bitte diesen Text in die angegebene Datei kopieren.</p>"
                +"<textarea cols=\"60\" rows=\"8\">"+data.htaccess+"</textarea>");
              $('textarea').textinput();
              $.mobile.navigate("#fehler_ausgeben");
        } else {
           alert(data.message);
           $.mobile.navigate("#installation_abschliessen");
        }
      },
      function (error) {
        alert("Fehler aufgetreten: "+error.statusText);
      }
    );
  };
  
  // Zur Initialisierung onCheckSystem aufrufen
  self.onCheckSystem();
}

function AnalysisModel() {
  var self = this;

  self.results = ko.observableArray([]);

  self.hasResults = ko.computed(function() {
    if(!!self.results()) {
      return self.results().length;
    } else {
      return 0;
    }
  });
}

// Model für die Datenbank-Verbindungsdaten
function DatabaseModel() {
  var self = this;
  self.hostname = ko.observable("localhost");
  self.port = ko.observable("3306");
  self.database = ko.observable("fibu");
  self.username = ko.observable("");
  self.password = ko.observable("");

  // Methode für onTestConnection
  self.onTestConnection = function(obj) {
      var param = ko.toJSON(self);
      doPOST("installation", "checkdbsettings", param,
          // Erfolgsfall: Datenbankverbindung ist brauchbar
          function(data) {
              alert('Die angegebenen Verbindungsdaten sind brauchbar: '+data);
          }, 
          // Fehlerfall: Verbindungsdaten sind unbrauchbar
          function(error) {
              alert('Mit den angegebenen Daten kann keine Verbindung aufgebaut werden: '+error.statusText);
          }
      );
  };

  // Methode für storeConnectionSettings
  self.onStoreConnection = function(obj) {
      var param = ko.toJSON(self);
      doPOST("installation", "storedbsettings", param,
          // Erfolgsfall: Datenbankverbindung ist brauchbar
          function(data) {
            if(data.isError) {
              $("#fehler_ausgeben_meldung").html("<h2>"+data.message
                +"</h2><p>Bitte diesen Text in die angegebene Datei kopieren.</p><textarea cols=\"60\" rows=\"8\">"+data.content+"</textarea>");
              $('textarea').textinput();
              $.mobile.navigate("#fehler_ausgeben");
            } else {
              alert('Die angegebenen Verbindungsdaten wurden gespeichert: '+data.message);
              $.mobile.navigate("#database_create_schema");
            }
          },
          // Fehlerfall: Verbindungsdaten sind unbrauchbar
          function(error) {
              alert('Die Datei konnte nicht gespeichert werden: '+error.statusText);
          }
      );
 
  }
}

// Model für die erfassten Haushaltsbuch-Benutzer
function UserModel() {
  var self = this;
  self.username = ko.observable("");
  self.password = ko.observable("");

  // Methode für createUser
  self.onCreateUser = function() {
    var param = ko.toJSON(self);
    doPOST("installation", "adduser", param,
      function(data) {
        if(data.isError) {
          $("#fehler_ausgeben_meldung").html("<h2>"+data.message+"</h2>"
               +"<p>Bitte diesen Text in die angegebene Datei kopieren.</p>"
               +"<textarea>"+data.htpasswd+"</textarea>");
          $("textarea").textinput();
          $.mobile.navigate("#fehler_ausgeben");
        } else {
          alert("Der angegebene Benutzer wurde gespeichert");
        }
      }, 
      function(error) {
         alert('Fehler aufgetreten: '+error.statusText);
      }
    );
  };
}

// Model initialisieren und in Knockout.js laden
var model = new InstallerModel();
$(document).ready(function() {
  ko.applyBindings(model);
});
