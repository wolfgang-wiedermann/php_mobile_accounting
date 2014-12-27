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
* Modell-Typ für Quick-Buchung
*/
function Quick(data) {
   var self = this;
   self.config_id = ko.observable(data.config_id);
   self.config_knz = ko.observable(data.config_knz);
   self.buchungstext = ko.observable(data.buchungstext);
   self.sollkonto = ko.observable(data.sollkonto);
   self.habenkonto = ko.observable(data.habenkonto);
   self.betrag = ko.observable(data.betrag);
   self.mandant_id = ko.observable(data.mandant_id);
}

/*
* Konstruktor-Funktion für das Knockout.js Model
*/
function AppViewModel() {
    var self = this;
    // Einfache Attribute
    self.sollhaben = ko.observable("S");
    self.konto = ko.observable(new Konto({'kontonummer':1234, 'bezeichnung':'Leer', 'kontenart_id':1}));
    self.buchung = ko.observable(new Buchung({'buchungsnummer':0, 'buchungstext':'', 'sollkonto':'0000', 'habenkonto':'0000'
        , 'betrag':'0.0', 'datum':'2013-01-01', 'benutzer':''}));

    // Kontenarten
    m.privat.initKontenarten(self);
    // Konten
    m.privat.initKonten(self);  
    // Schnellbuchungen
    m.privat.initQuick(self);
    // Buchungsmonate
    m.privat.initMonate(self);
    // Buchungsjahre
    m.privat.initJahre(self);

    // Buchungen
    self.saveBuchung = function(tmpModel) {
        buchungenForm.create(ko.toJSON(tmpModel.buchung()));
    }
    // Buchungs-Queue (Offline-Buchungen)
    m.privat.initQueuedBuchungen(self);
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
        doGETwithCache("kontenart", "list", [], 
            function(data) {
                self.kontenarten($.map(data, function(item) {return new Kontenart(item); }));
            }, 
            function(error) {
                alert("Fehler aufgetreten: "+JSON.stringfy(error));
            }
        );
    };
    handlers.refreshKontenarten = self.refreshKontenarten;
    self.refreshKontenarten();

    self.loadKontenart = function(kontenart_id) {
        doGETwithCache("kontenart", "get", {'id':kontenart_id},
            function(data) {
                self.kontenart().kontenart_id(data.kontenart_id);
                self.kontenart().bezeichnung(data.bezeichnung);
            },
            function(error) {
                alert("Fehler aufgetreten: "+JSON.stringify(error));
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
    self.konten_aktiv = ko.observableArray([]);

    // Model fuer Konten-Mehrfachauswahl
    self.konten_selectable = ko.observableArray([]);
    self.konten_selected = ko.observableArray([]);

    self.refreshKonten = function () {    
        doGETwithCache("konto", "list", [], 
            function(data) {
                self.konten($.map(data, function(item) {return new Konto(item) }));
                self.konten_aktiv($.map(data, function(item) { if(item.kontenart_id == '1') { return new Konto(item); }}));
                self.konten_selectable($.map(data, function(item) {return new Konto(item); }));
                self.konten_selected.removeAll();

                $(".konto_auswahl_button").unbind("click");
                $(".konto_auswahl_button").click(kontenForm.kontoAuswahlHandler);
            }, 
            function(error) {
                alert("Fehler aufgetreten: "+JSON.stringify(error));
            }
        );
    };
    handlers.refreshKonten = self.refreshKonten;
    self.refreshKonten();

    // Konto zu self.konten_selected hinzufügen
    self.selectKonto = function(konto) {
        // Prüfen, ob das Konto zu den anderen selektierten kompatibel ist...
        var art = util.intDivision((konto.kontenart_id()*1)+1, 2);
        var selected = self.konten_selected();
        var ok = true;
        for(var i = 0; i < selected.length; i++) {
            if(art != util.intDivision((selected[i].kontenart_id()*1)+1, 2)) {
                ok = false;
            }
        }
        if(ok) {
            self.konten_selectable.remove(konto);
            self.konten_selected.push(konto);
        } else {
            alert("Das Konto "+konto.tostring()+" ist nicht mit ihrer restlichen Auswahl kompatibel");
        }
    }

    // Konto aus self.konten_selected wieder entfernen
    self.unselectKonto = function(konto) {
        self.konten_selected.remove(konto);
        self.konten_selectable.push(konto);
    }

    // Konto auswählen und monatliche Cashflow-Darstellung laden
    self.selectKontoForCashFlow = function(konto) {
        ergebnisForm.loadCacheFlow(konto);
    }

    self.loadKonto = function(kontoNummer) {
        doGETwithCache("konto", "get", {'id':kontoNummer},
            function(data) {
                 self.konto().kontonummer(data.kontonummer);
                 self.konto().bezeichnung(data.bezeichnung);
                 self.konto().kontenart_id(data.kontenart_id);
                 $("#account_from_edit_kontenart").selectmenu("refresh", true);
            },
            function(error) {
                alert("Fehler aufgetreten: "+JSON.stringify(error));
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

m.privat.initQuick = function(self) {
    self.quickentries = ko.observableArray([]);
    self.selectedquick = new Quick({config_id:0, config_knz:'', sollkonto:'', habenkonto:''
                                    , buchungstext:'', betrag:0, mandant_id:0});
    self.refreshQuick = function () {
        doGETwithCache("menu", "quick", [],
            function(data) {
                self.quickentries($.map(data, function(item) {return new Quick(item) }));
            },
            function(error) {
                alert("Fehler aufgetreten: "+JSON.stringify(error));
            }
        );
    };

    /*
    * Event-Handler für Klicks auf die Quick-Einträge
    * (zum Öffnen der Bearbeiten-Maske)
    */
    self.onQuickClick = function(quick) {
        self.selectedquick.config_id(quick.config_id());
        self.selectedquick.config_knz(quick.config_knz());
        self.selectedquick.sollkonto(quick.sollkonto());
        self.selectedquick.habenkonto(quick.habenkonto());
        self.selectedquick.buchungstext(quick.buchungstext());
        self.selectedquick.betrag(quick.betrag());
        self.selectedquick.mandant_id(quick.mandant_id());

	adminForm.loadAdminQuickFormView(quick);
    };
    handlers.refreshQuick = self.refreshQuick;
    self.refreshQuick();

    /*
    * Event-Handler für Klicks auf den Speichern-Button
    * in der Bearbeiten-Maske des aktuellen Quick-Eintrags
    */
    self.saveSelectedQuick = function() {
        adminForm.saveAdminQuickTemplate(self.selectedquick);
    };

    /*
    * Event-Handler für Klicks auf den Löschen-Button
    * in der Bearbeiten-Maske des aktuellen Quick-Eintrags
    */
    self.deleteSelectedQuick = function() {
        adminForm.deleteAdminQuickTemplate(self.selectedquick);
    }
};

/*
* Auswählbare Monate für Berichtsauswertung nach Monat laden und bereitstellen
*/
m.privat.initMonate = function(self) {
    self.buchungsmonate = ko.observableArray([]);
    self.selectedMonat = ko.observable('201403'); 

    self.updateMonate = function(successHandler) {
        doGETwithCache("ergebnis", "months", [], 
            function(data) {
                self.buchungsmonate.removeAll();
                for(var i = 0; i < data.length; i++) { 
                    if(i+1 === data.length) {
                        self.buchungsmonate.push({'monat':data[i], 'selected':true});
                        self.selectedMonat(data[i]);
                    } else {
                        self.buchungsmonate.push({'monat':data[i], 'selected':false});
                    }
                }

                if(!!successHandler) {
                    successHandler();
                }
            }, 
            function(error) {
                alert("Fehler aufgetreten: "+JSON.stringify(error));
            });
    };
    self.updateMonate();

    self.updateMonateSimple = function() {
        self.updateMonate(function() {
            $("#ergebnis_form_guv_months").selectmenu('refresh');
        });
    }
};

/*
* Auswählbare Buchungsjahre für Berichtsauswertung nach Jahren laden und im Model bereitstellen
*/
m.privat.initJahre = function(self) {
    self.buchungsjahre = ko.observableArray([]);
    self.selectedJahr = ko.observable('2014');

    self.updateJahre = function(successHandler) {
        doGETwithCache("ergebnis", "years", [], 
            function(data) {
                self.buchungsjahre.removeAll();
                for(var i = 0; i < data.length; i++) {
                    if(i+1 === data.length) {
                        self.buchungsjahre.push({'jahr':data[i], 'selected':true});
                        self.selectedMonat(data[i]);
                    } else {
                        self.buchungsjahre.push({'jahr':data[i], 'selected':false});
                    }
                }

                if(!!successHandler) {
                    successHandler();
                }
            }, 
            function(error) {
                alert("Fehler aufgetreten: "+JSON.stringify(error));
            });
    };
    self.updateJahre();

    self.updateJahreSimple = function() {
        self.updateJahre(function() {
            $("#ergebnis_form_guv_years").selectmenu('refresh');
        });
    }
}

/*
* Computed-Observable für die Buchungsqueue im Model registrieren
*/
m.privat.initQueuedBuchungen = function(self) {
    self.buchungsqueue = ko.observableArray([]);

    self.refreshBuchungsqueue = function() {
        var queue = broker.queue.list("buchung", "create");
        self.buchungsqueue.destroyAll();

        return queue.forEach(function(item) {
            self.buchungsqueue.push(JSON.parse(item.parameterObject));
        });
    };

    broker.queue.addNewItemAddedListener(self.refreshBuchungsqueue);
    broker.queue.addItemRemovedListener(self.refreshBuchungsqueue);

};
