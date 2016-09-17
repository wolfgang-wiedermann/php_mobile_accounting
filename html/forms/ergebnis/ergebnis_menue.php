<div data-role="page" data-theme="b" id="ergebnis_menue">
  <div data-role="header" data-position="fixed">
    <a href="#hauptmenue" data-role="button" data-icon="home" data-bind="text:i18n.general.home"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview" data-filter="false">
      <li data-role="list-divider" data-bind="text:i18n.ergebnis.standardauswertungen"></li>
      <li data-theme="c"><a data-bind="click: ergebnis().bilanz, text:i18n.ergebnis.bilanz" href="#ergebnis_view"></a></li>
      <li data-theme="c"><a data-bind="click: ergebnis().guvjahr, text:i18n.ergebnis.guvjahr" href="#ergebnis_view"></a></li>
      <li data-theme="c"><a data-bind="click: ergebnis().guvmonat, text:i18n.ergebnis.guvmonat" href="#ergebnis_view"></a></li>
      <li data-theme="c"><a data-bind="click: ergebnis().guvprognose, text:i18n.ergebnis.guvprognose" href="#ergebnis_view"></a></li>
      <li data-role="list-divider" data-bind="text:i18n.verlauf.verlaufsauswertungen"></li>
      <li data-theme="c"><a data-bind="click: verlauf().verlaufaufwand, text:i18n.verlauf.verlaufaufwand" href="#ergebnis_view"></a></li>
      <li data-theme="c"><a data-bind="click: verlauf().verlaufertrag, text:i18n.verlauf.verlaufertrag" href="#ergebnis_view"></a></li>
      <li data-theme="c"><a data-bind="click: verlauf().verlaufgewinn, text:i18n.verlauf.verlaufgewinn" href="#ergebnis_view"></a></li>
      <li data-theme="c"><a data-bind="click: verlauf().verlaufintern, text:i18n.verlauf.verlaufintern" href="#ergebnis_view"></a></li>
      <li data-theme="c"><a data-bind="click: verlauf().verlaufzuundabfluss, text:i18n.verlauf.verlaufzuundabfluss" href="#verlauf_kontenliste"></a></li>
      <li data-theme="c"><a data-bind="click: verlauf().verlauffrei, text:i18n.verlauf.verlauffrei" href="#ergebnis_view"></a></li>
      <li data-role="list-divider" data-bind="text:i18n.export.datenexport"></li>
      <li data-theme="c"><a data-bind="click: exporte().export_journal, text:i18n.export.journal" href="#ergebnis_view">Journal exportieren</a></li>
      <li data-theme="c"><a data-bind="click: exporte().export_guv, text:i18n.export.guvmonate" href="#ergebnis_view">GuV Monate exportieren</a></li>
      <li data-theme="c"><a data-bind="click: exporte().export_bilanz, text:i18n.export.bilanzmonate" href="#ergebnis_view">Bilanz Monate exportieren</a></li>
      <li data-theme="c"><a data-bind="click: exporte().export_sqlbackup, text:i18n.export.sqlbackup" href="#ergebnis_view">Backup exportieren</a></li>
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
