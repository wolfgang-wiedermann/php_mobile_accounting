<div data-role="page" id="ergebnis_menue">
  <div data-role="header" data-position="fixed">
    <a href="#hauptmenue" data-role="button" data-icon="home">Men&uuml;</a>
    <h1>Buchhaltung</h1>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview" data-filter="false">
      <li data-role="list-divider">Standard-Auswertungen</li>
      <li><a data-bind="click: ergebnis().bilanz" href="#ergebnis_view">Bilanz</a></li>
      <li><a data-bind="click: ergebnis().guvjahr" href="#ergebnis_view">Gewinn und Verlust</a></li>
      <li><a data-bind="click: ergebnis().guvmonat" href="#ergebnis_view">GuV (Monat)</a></li>
      <li><a data-bind="click: ergebnis().guvprognose" href="#ergebnis_view">GuV Prognose</a></li>
      <li data-role="list-divider">Verlaufs-Auswertungen</li>
      <li><a data-bind="click: ergebnis().verlaufaufwand" href="#ergebnis_view">Aufwand (Monate)</a></li>
      <li><a data-bind="click: ergebnis().verlaufertrag" href="#ergebnis_view">Ertrag (Monate)</a></li>
      <li><a data-bind="click: ergebnis().verlaufgewinn" href="#ergebnis_view">Gewinn (Monate)</a></li>
      <li><a data-bind="click: ergebnis().verlaufintern" href="#ergebnis_view">Monatsinterner Verlauf</a></li>
      <li><a data-bind="click: ergebnis().verlaufzuundabfluss" href="#ergebnis_view">Zu- und Abfluss</a></li>
      <li><a data-bind="click: ergebnis().verlauffrei" href="#ergebnis_view">Frei kombiniert</a></li>
      <li data-role="list-divider">Datenexport</li>
      <li><a data-bind="click: ergebnis().exportjournal" href="#ergebnis_view">Journal exportieren</a></li>
      <li><a data-bind="click: ergebnis().exportguvmonat" href="#ergebnis_view">GuV Monate exportieren</a></li>
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4>&copy; by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
