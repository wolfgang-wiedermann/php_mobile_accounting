/*
* Leeres JSON-Objekt, in dem die Event-Handler für die Knockout.js 
* Events hinterlegt werden können
*/
var handlers = {};

/*
* Modell-Typ für Konto
*/
function Konto(data) {
    var self = this;
    self.kontonummer = ko.observable(data.kontonummer);
    self.bezeichnung = ko.observable(data.bezeichnung);
    self.kontenart_id = ko.observable(data.kontenart_id);
    self.tostring = ko.computed(function() {
        return self.kontonummer()+" : "+self.bezeichnung();
    });
}

/*
* Modell-Typ für Kontenart
*/
function Kontenart(data) {
    var self = this;
    self.kontenart_id = ko.observable(data.kontenart_id);
    self.bezeichnung = ko.observable(data.bezeichnung);
}

/*
* Modell-Typ für Buchungssatz
*/
function Buchung(data) {
   var self = this;
   self.buchungsnummer = ko.observable(data.buchungsnummer);
   self.buchungstext = ko.observable(data.buchungstext);
   self.sollkonto = ko.observable(data.sollkonto);
   self.habenkonto = ko.observable(data.habenkonto);
   self.betrag = ko.observable(data.betrag);
   self.datum = ko.observable(data.datum);
   self.benutzer = ko.observable(data.benutzer);
}

/*
* Konstruktor-Funktion für das Knockout.js Model
*/
function AppViewModel() {
    var self = this;
    // Einfache Attribute
    self.konto = ko.observable(new Konto({'kontonummer':1234, 'bezeichnung':'Leer', 'kontenart_id':1}));
    self.buchung = ko.observable(new Buchung({'buchungsnummer':0, 'buchungstext':'', 'sollkonto':'0000', 'habenkonto':'0000'
        , 'betrag':'0.0', 'datum':'2013-01-01', 'benutzer':''}));

    // Kontenarten
    m.privat.initKontenarten(self);
    // Konten
    m.privat.initKonten(self);  
    // Buchungen
    self.saveBuchung = function(tmpModel) {
        buchungenForm.create(ko.toJSON(tmpModel.buchung()));
    }
  
}

if(!m) {
    var m = { privat: {}}; 
} else {
    m.privat = {};
}

/*
* Kontenarten initialisieren, zugehörige EventHandler eintragen etc.
*/
m.privat.initKontenarten = function(self) {
    // Kontenarten
    self.kontenarten = ko.observableArray([]);
    self.refreshKontenarten = function() {
        doGET("kontenart", "list", [], 
            function(data) {
                self.kontenarten($.map(data, function(item) {return new Kontenart(item); }));
            }, 
            function(error) {
                alert("Fehler aufgetreten: "+error);
            }
        );
    };
    handlers.refreshKontenarten = self.refreshKontenarten;
    self.refreshKontenarten();

    self.loadKontenart = function(kontenart_id) {
        doGET("kontenart", "get", {'id':kontenart_id},
            function(data) {
                self.kontenart().kontenart_id(data.kontenart_id);
                self.kontenart().bezeichnung(data.bezeichnung);
            },
            function(error) {
                alert("Fehler aufgetreten: "+error);
            }
        );
    };
    handlers.loadKontenart = self.loadKontenart;

};

/*
* Konten im Model initialisieren
*/
m.privat.initKonten = function(self) {
    // Konten
    self.konten = ko.observableArray([]);
    self.refreshKonten = function () {    
        doGET("konto", "list", [], 
            function(data) {
                self.konten($.map(data, function(item) {return new Konto(item) }));
                $(".konto_auswahl_button").unbind("click");
                $(".konto_auswahl_button").click(kontenForm.kontoAuswahlHandler);
            }, 
            function(error) {
                alert("Fehler aufgetreten: "+error);
            }
        );
    };
    handlers.refreshKonten = self.refreshKonten;
    self.refreshKonten();

    self.loadKonto = function(kontoNummer) {
        doGET("konto", "get", {'id':kontoNummer},
            function(data) {
                 //self.konto(new Konto(data));
                 self.konto().kontonummer(data.kontonummer);
                 self.konto().bezeichnung(data.bezeichnung);
                 self.konto().kontenart_id(data.kontenart_id);
                 $("#account_from_edit_kontenart").selectmenu("refresh", true);
            },
            function(error) {
                alert("Fehler aufgetreten: "+error);
            }
        );
    };
    handlers.loadKonto = self.loadKonto;

    self.saveKonto = function(tmpModel) {        
        kontenForm.save(ko.toJSON(tmpModel.konto()));
        console.log(ko.toJSON(tmpModel.konto()));
    }

    self.createKonto = function(tmpModel) {
        kontenForm.create(ko.toJSON(tmpModel.konto()));
    }
};
