<div data-role="page" data-theme="b" id="verlauf_kontenauswahl">
  <div data-role="header" data-position="fixed">
    <a href="#ergebnis_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <h4 data-bind="text:i18n.verlauf.kontenauswahl"></h4>
    <ul data-role="listview" data-inset="false" class="konten_liste2">
      <!-- ko foreach: verlauf().konten_selectable -->
      <li data-theme="c"><a data-bind="text: tostring, click: $root.verlauf().verlauffrei_select"></a></li>
      <!-- /ko -->
    </ul>
    <h4 data-bind="text:i18n.verlauf.ausgewaehltekonten"></h4>
    <ul data-role="listview" data-inset="false" class="konten_liste2">
        <!-- ko foreach: verlauf().konten_selected -->
        <li data-theme="c"><a data-bind="text: tostring, click: $root.verlauf().verlauffrei_deselect"></a></li>
        <!-- /ko -->
    </ul>
    <br/>
    <button data-bind="click: verlauf().showVerlauffrei, text:i18n.verlauf.anzeigen"></button>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
