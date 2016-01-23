<!DOCTYPE html>
<html lang="de">
<head>
<meta name="viewport" content="width:device-width, initial-scale=1">
<link rel="stylesheet" href="./html/css/lib/haushaltsbuch-theme.min.css" />
<link rel="stylesheet" href="./html/css/lib/jquery.mobile.icons.min.css" />
<link rel="stylesheet" href="./html/css/lib/jquery.mobile.structure-1.4.5.min.css" />
<!-- Standard-Bibliotheken -->
<script src="./html/js/jquery-2.1.3.min.js"></script>
<script src="./html/js/jquery.mobile-1.4.5.min.js"></script>
<script src="./html/js/knockout-3.3.0.js"></script>
<!-- Installer-spezifische Code-Dateien -->
<script src="./install/install.js"></script>
<title>html5-haushaltsbuch Installation</title>
</head>
<body>

<!-- Startseite -->
<div data-role="page" data-theme="b" id="startseite">
<div data-role="header">
  <h1>Installation</h1>
</div>
<div data-role="content">
Installationsprogramm f&uuml;r das HTML5-Haushaltsbuch.<br>
<!-- ko if: analysis.hasResults -->
<br/>
Die Analyse Ihres Systems ergab die folgenden Hinweise:
<ul data-bind="foreach: analysis.results">
  <li data-bind="text: description"></li>
</ul>
<!-- /ko -->
Bitte folgen Sie den Anweisungen auf dem Bildschirm, das Programm wird Sie durch die Installation des HTML5-Haushaltsbuchs leiten.<br>
<br>
Bitte beginnen Sie die Installation durch einen Klick auf den Button "Weiter"<br>
<br>
<a href="#database_config" id="weiter_btn_startseite" data-role="button"
   data-icon="arrow-r" data-iconpos="right">Weiter</a>
</div>
</div>

<!-- Datenbankkonfiguration -->
<div data-role="page" data-theme="b" id="database_config">
<div data-role="header">
  <h1>Datenbankkonfiguration</h1>
</div>
<div data-role="content">
<label for="database_host_tf">Hostname</label>
<input id="database_host_tf" type="text" data-bind="value:database.hostname"/>
<label for="database_port_tf">Port</label>
<input id="database_port_tf" type="number" data-bind="value:database.port"/>
<label for="database_name_tf">Datenbankname</label>
<input id="database_name_tf" type="text" data-bind="value:database.database"/>
<label for="database_user_tf">Datenbankbenutzer</label>
<input id="database_user_tf" type="text" data-bind="value:database.username"/>
<label for="database_password_tf">Datenbankpasswort</label>
<input id="database_password_tf" type="password" data-bind="value:database.password"/>
<br/>
<button id="test_btn_database_config" data-bind="click:database.onTestConnection">Verbindung testen</button>
<a href="#database_create_schema" id="weiter_btn_database_config" data-role="button" 
   data-icon="arrow-r" data-iconpos="right" data-bind="click:database.onStoreConnection">Weiter</a>
</div>
</div>

<!-- Datenbankschema generieren -->
<div data-role="page" data-theme="b" id="database_create_schema">
<div data-role="header">
  <h1>Datenbankschema anlegen</h1>
</div>
<div data-role="content">
Im n&auml;chsten Schritt wird in der von Ihnen angegebenen Datenbank das Datenbankschema f&uuml;r 
das HTML5-Haushaltsbuch generiert. Dazu werden verschiedene Tabellen und Views mit dem Prefix fi_
angelegt. <br/>
<br/>
Optimalerweise ist die von Ihnen angegebene Datenbank leer, dann wird es zu keinen Problemen kommen
(sofern der von Ihnen angegebene Benutzer Berechtigungen zum Anlegen von Tabellen und Views besitzt).
Enth&auml;lt Ihre Datenbank bereits Tabellen einer anderen Anwendung, so pr&uuml;fen Sie bitte ob
diese zuverl&auml;ssig <b>nicht</b> mit fi_ beginnen.<br/>
<br/>
Ist Ihre Datenbank leer oder enth&auml;lt sie keine Tabellen, die mit fi_ beginnen, so k&ouml;nnen
Sie durck einen Klick auf den Button "Weiter" das Datenbankschema generieren und 
mit der Installation fortfahren.<br/>
<a href="#user_config" id="weiter_btn_database_create_schema" data-role="button" 
   data-icon="arrow-r" data-iconpos="right" data-bind="click:onCreateDbSchema">Weiter</a>
</div>
</div>

<!-- Benutzer anlegen -->
<div data-role="page" data-theme="b" id="user_config">
<div data-role="header">
  <h1>Benutzer anlegen</h1>
</div>
<div data-role="content">
Im folgenden Abschnitt der Installation m&uuml;ssen Sie einen Benutzer anlegen, mit dem
Sie sich sp&auml;ter am Haushaltsbuch anmelden k&ouml;nnen.
<label for="user_name_tf">Benutzername</label>
<input id="user_name_tf" type="text" data-bind="value:user.username"/>
<label for="user_password_tf">Passwort</label>
<input id="user_password_tf" type="password" data-bind="value:user.password"/>
<button id="user_create_btn" data-bind="click:user.onCreateUser">Benutzer anlegen</button>
<ul id="user_created_users_list">
</ul>
<a id="finish_installation_btn" data-role="button"
   data-icon="arrow-r" data-iconpos="right" data-bind="click:onActivateHtaccess">Installation abschlie&szlig;en</a>
</div>
</div>

<!-- Installation abschließen -->
<div data-role="page" data-theme="b" id="installation_abschliessen">
<div data-role="header">
  <h1>Installation abgeschlossen</h1>
</div>
<div data-role="content">
Die Installation des HTML5-Haushaltsbuchs ist hiermit erfolgreich abgeschlossen. 
Bitte <b>l&ouml;schen Sie nun die Datei install.php sowie den Ordner install</b> aus dem Wurzelverzeichnis
des HTML5-Haushaltsbuchs.<br/>
<br/>
<br/>
<a href="./html/index.php" data-role="button" data-icon="arrow-r"
   data-iconpos="right" data-ajax="false">Haushaltsbuch starten</a>
</div>
</div>

<!-- Fehler ausgeben -->
<div data-role="page" data-theme="b" data-dialog="true" id="fehler_ausgeben">
<div data-role="header">
  <h1>Fehler aufgetreten</h1>
</div>
<div data-role="content">
  <div id="fehler_ausgeben_meldung">
  </div>
  <a href="javascript:history.back()" data-role="button">Zur&uuml;ck</a>
</div>
</div>

</body>
</html>
