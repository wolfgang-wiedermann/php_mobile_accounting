<?php define("MAIN_PAGE", 1); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/jquery.mobile-1.3.1.css" />
    <script src="./js/jquery-2.0.2.min.js"></script>
    <script src="./js/jquery.mobile-1.3.1.js"></script>
    <script src="./js/knockout-2.2.1.js"></script>
    <script src="./js/helper.js"></script>
</head>
<body>
<div data-role="page" id="main_menu" class="page">
    <div data-role="header" data-position="fixed" data-theme="b" class="clickable_header">
        <a href="#" id="header_home_button" data-icon="home">Men&uuml;</a>
	<h1 id="header_text">Buchhaltung</h1>
        <a href="#" id="header_new_button" data-icon="new">Neu</a>
    </div>
    <div data-role="content">
        <?php include("./parts/navigation.php"); ?>
        <?php include("./parts/account_form.php"); ?>
        <?php include("./parts/buchung_form.php"); ?>
        <?php include("./parts/ergebnis_form.php"); ?>
    </div>
    <div data-role="footer" data-position="fixed" data-theme="b"><center>&copy; 2013 by Wolfgang Wiedermann</center></div>
</div>
<script type="text/javascript">
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

var handlers = {};

function AppViewModel() {
    var self = this;
    // Einfache Attribute
    self.konto = ko.observable(new Konto({'kontonummer':1234, 'bezeichnung':'Leer', 'kontenart_id':1}));
    self.buchung = ko.observable(new Buchung({'buchungsnummer':0, 'buchungstext':'', 'sollkonto':'0000', 'habenkonto':'0000', 'betrag':'0.0', 'datum':'2013-01-01', 'benutzer':''}));

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

    self.saveBuchung = function(tmpModel) {
        alert(ko.toJSON(tmpModel.buchung()));
        buchungenForm.create(ko.toJSON(tmpModel.buchung()));
    }
}

var model = new AppViewModel();

$(document).ready(function() {
    ko.applyBindings(model);
    gotoMainPage();
    $("#header_home_button").click(gotoMainPage);
    $("#menu_konten").click(gotoKonten);
    $("#menu_buchen").click(gotoBuchen);
    $("#menu_auswerten").click(gotoAuswerten);
});

function gotoMainPage() {
    //alert("GotoMainPage");
    $("#header_text").text("Buchhaltung");
    $(".content_form").hide();
    $("#navigation").show();
    $("#header_new_button").hide();
    $("#header_home_button").hide();
}

function gotoKonten() {
    $(".content_form").hide();
    $("#account_form").show();
    $("#header_new_button").show();
    $("#header_home_button").show();
    $("#header_new_button").unbind("click");
    $("#header_new_button").click(kontenForm.newKontoHandler);
    handlers.refreshKonten();
}

function gotoBuchen() {
    $(".content_form").hide();
    $("#header_home_button").show();
    $("#buchung_form").show();
    handlers.refreshKonten();
    buchungenForm.registerBuchungFormEvents();
}

function gotoAuswerten() {
    $(".content_form").hide();
    $("#header_home_button").show();
    $("#ergebnis_form").show();
    ergebnisForm.registerErgebnisFormEvents();
}
</script>
</body>
</html>
