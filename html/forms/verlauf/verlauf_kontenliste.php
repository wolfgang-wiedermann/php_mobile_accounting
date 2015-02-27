<div data-role="page" id="verlauf_kontenliste">
  <div data-role="header" data-position="fixed">
    <a href="#ergebnis_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <!-- Hier eine Combo-Box einbauen -->
    <ul data-role="listview" data-inset="false" data-filter="true" class="konten_liste">
      <!-- ko foreach: konten().aktivkonten -->
      <li><a data-bind="text: tostring"></a></li>
      <!-- /ko -->
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
