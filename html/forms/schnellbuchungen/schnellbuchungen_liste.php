<div data-role="page" id="schnellbuchungen_liste">
  <div data-role="header">
    <a href="#hauptmenue" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1>Buchhaltung</h1>
    <a href="#schnellbuchung_neu" data-role="button" data-icon="plus">Schnellbuchung anlegen</a>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview" data-inset="false" data-filter="true">
      <!-- ko foreach: schnellbuchungen().schnellbuchungen -->
      <li><a data-bind="text: config_knz, click: $root.schnellbuchungen().openForEdit"></a></li>
      <!-- /ko -->
    </ul>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>&copy; 2013-2015 by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
