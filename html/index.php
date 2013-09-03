<?php define("MAIN_PAGE", 1); ?>
<!DOCTYPE html>
<html lang="de" manifest="manifest.php">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/jquery.mobile-1.3.1.css" />
    <!-- Standard-Bibliotheken -->
    <script src="./js/jquery-2.0.2.min.js"></script>
    <script src="./js/jquery.mobile-1.3.1.js"></script>
    <script src="./js/knockout-2.2.1.js"></script>
    <!-- App-spezifische Code-Dateien -->
    <script src="./js/helper.js"></script>
    <script src="./js/model.js"></script>
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
var model = new AppViewModel();

/*
* Konstruktor-Funktion, initialisiert nach dem Laden der Seite
* die Ansicht und registriert die Event-Handler der Haupt-View
*/
$(document).ready(function() {
    updateHtml5AppCache();
    ko.applyBindings(model);
    gotoMainPage();
    $("#header_home_button").click(gotoMainPage);
    $("#menu_konten").click(gotoKonten);
    $("#menu_buchen").click(gotoBuchen);
    $("#menu_auswerten").click(gotoAuswerten);
    $("#menu_schnellbuchungen_divider").click(menu.loadQuickMenuItems);
});

/*
* Cache neu laden ...
* vgl. http://www.html5rocks.com/de/tutorials/appcache/beginner/
*/
function updateHtml5AppCache() {
    var appCache = window.applicationCache;
    if(!!appCache) {
    appCache.update();
        if(appCache.status == window.applicationCache.UPDATEREADY) {
            appCache.swapCache();
        }
    }
}

/*
* Handler fuer das Klick-Event des Home-Buttons
*/
function gotoMainPage() {
    $("#header_text").text("Buchhaltung");
    $(".content_form").hide();
    $("#navigation").show();
    $("#header_new_button").hide();
    $("#header_home_button").hide();
}

/*
* Handler fuer das Klick-Event des Konten-Buttons
*/
function gotoKonten() {
    $(".content_form").hide();
    $("#account_form").show();
    $("#header_new_button").show();
    $("#header_home_button").show();
    $("#header_new_button").unbind("click");
    $("#header_new_button").click(kontenForm.newKontoHandler);
    handlers.refreshKonten();
}

/*
* Handler fuer das Klick-Event des Buchen-Buttons
*/
function gotoBuchen() {
    $(".content_form").hide();
    $("#header_home_button").show();
    $("#buchung_form").show();
    handlers.refreshKonten();
    buchungenForm.registerBuchungFormEvents();
}

/*
* Handler fuer das Klick-Event des Auswerten-Buttons
*/
function gotoAuswerten() {
    $(".content_form").hide();
    $("#header_home_button").show();
    $("#ergebnis_form").show();
    ergebnisForm.registerErgebnisFormEvents();
}
</script>
</body>
</html>
