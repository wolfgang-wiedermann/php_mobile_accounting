<div data-role="page" data-theme="b" id="verlauf_kontenliste">
  <div data-role="header" data-position="fixed">
    <a href="#ergebnis_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <div>
        <select data-bind="value: verlauf().sollhaben">
            <option value="S">Soll-Buchungen</option>
            <option value="H">Haben-Buchungen</option>
        </select>
        <br/>
    </div>
    <!-- Hier eine Combo-Box einbauen -->
    <ul data-role="listview" data-inset="false" class="konten_liste">
      <!-- ko foreach: konten().aktivkonten -->
      <li data-theme="c"><a data-bind="text: tostring, click: $root.verlauf().showVerlaufzuundabfluss"></a></li>
      <!-- /ko -->
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
