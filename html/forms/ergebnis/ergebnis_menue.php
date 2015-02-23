<div data-role="page" id="ergebnis_menue">
  <div data-role="header" data-position="fixed">
    <a href="#hauptmenue" data-role="button" data-icon="home" data-bind="text:i4l.general.home"></a>
    <h1 data-bind="text:i4l.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview" data-filter="false">
      <li data-role="list-divider" data-bind="text:i4l.ergebnis.standardauswertungen"></li>
      <li><a data-bind="click: ergebnis().bilanz, text:i4l.ergebnis.bilanz" href="#ergebnis_view"></a></li>
      <li><a data-bind="click: ergebnis().guvjahr, text:i4l.ergebnis.guvjahr" href="#ergebnis_view"></a></li>
      <li><a data-bind="click: ergebnis().guvmonat, text:i4l.ergebnis.guvmonat" href="#ergebnis_view"></a></li>
      <li><a data-bind="click: ergebnis().guvprognose, text:i4l.ergebnis.guvprognose" href="#ergebnis_view"></a></li>
      <li data-role="list-divider" data-bind="text:i4l.verlauf.verlaufsauswertungen"></li>
      <li><a data-bind="click: verlauf().verlaufaufwand, text:i4l.verlauf.verlaufaufwand" href="#ergebnis_view"></a></li>
      <li><a data-bind="click: verlauf().verlaufertrag, text:i4l.verlauf.verlaufertrag" href="#ergebnis_view"></a></li>
      <li><a data-bind="click: verlauf().verlaufgewinn, text:i4l.verlauf.verlaufgewinn" href="#ergebnis_view"></a></li>
      <li><a data-bind="click: verlauf().verlaufintern, text:i4l.verlauf.verlaufintern" href="#ergebnis_view"></a></li>
      <li><a data-bind="click: verlauf().verlaufzuundabfluss, text:i4l.verlauf.verlaufzuundabfluss" href="#ergebnis_view"></a></li>
      <li><a data-bind="click: verlauf().verlauffrei, text:i4l.verlauf.verlauffrei" href="#ergebnis_view"></a></li>
      <li data-role="list-divider">Datenexport</li>
      <li><a data-bind="click: ergebnis().exportjournal" href="#ergebnis_view">Journal exportieren</a></li>
      <li><a data-bind="click: ergebnis().exportguvmonat" href="#ergebnis_view">GuV Monate exportieren</a></li>
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i4l.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
