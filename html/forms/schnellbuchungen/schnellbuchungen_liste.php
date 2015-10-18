<div data-role="page" data-theme="b" id="schnellbuchungen_liste">
  <div data-role="header" data-position="fixed">
    <a href="#hauptmenue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
    <a href="#schnellbuchung_anlegen" data-role="button" data-icon="plus"
       data-bind="click: $root.schnellbuchungen().openNewForm, text:i18n.schnellbuchungen.anlegen"></a>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview" data-inset="false" data-filter="true" id="s_liste">
      <!-- ko foreach: schnellbuchungen().schnellbuchungen -->
      <li data-theme="c"><a data-bind="text: config_knz, click: $root.schnellbuchungen().openForEdit"></a></li>
      <!-- /ko -->
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
