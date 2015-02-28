<div data-role="page" id="verlauf_kontenauswahl">
  <div data-role="header" data-position="fixed">
    <a href="#ergebnis_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <h4>Kontenauswahl</h4>
    <ul data-role="listview" data-inset="false" class="konten_liste2">
      <!-- ko foreach: verlauf().konten_selectable -->
      <li><a data-bind="text: tostring, click: $root.verlauf().verlauffrei_select"></a></li>
      <!-- /ko -->
    </ul>
    <h4>Ausgew&auml;hlte Konten</h4>
    <ul data-role="listview" data-inset="false" class="konten_liste2">
        <!-- ko foreach: verlauf().konten_selected -->
        <li><a data-bind="text: tostring, click: $root.verlauf().verlauffrei_deselect"></a></li>
        <!-- /ko -->
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
