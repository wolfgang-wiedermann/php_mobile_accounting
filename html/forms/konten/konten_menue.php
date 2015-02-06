<div data-role="page" id="konten_menue">
  <div data-role="header">
    <a href="#konten_liste" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1>Buchhaltung</h1>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview">
      <li><a href="#konto_saldo">Saldo anzeigen</a></li>
      <li><a href="#konto_monatssalden">Monatssalden</a></li>
      <li><a href="#konto_buchungen" data-bind="click: $root.konten().openBuchungen">Buchungen anzeigen</a></li>
      <li><a href="#konto_bearbeiten" data-bind="click: $root.konten().openKontenBearbeiten">Konto bearbeiten</a></li>
    </ul>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>&copy; 2013-2015 by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
