<!-- Formular: Schnellbuchung anlegen -->
<div data-role="page" id="schnellbuchung_anlegen">
  <div data-role="header" data-position="fixed">
    <a href="#schnellbuchungen_liste" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1 data-bind="text:i4l.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <label for="sb_config_knz">Kurzbezeichnung</label>
    <input type="text" id="sb_config_knz" data-bind="value: schnellbuchungen().selectedSchnellbuchung().config_knz">
    <label for="sb_buchungstext">Buchungstext</label>
    <input type="text" id="sb_buchungstext" data-bind="value: schnellbuchungen().selectedSchnellbuchung().buchungstext">
    <label for="sb_sollkonto">Soll-Konto</label>
    <select id="sb_sollkonto" data-bind="value: schnellbuchungen().selectedSchnellbuchung().sollkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label for="sb_habenkonto">Haben-Konto</label>
    <select id="sb_habenkonto" data-bind="value: schnellbuchungen().selectedSchnellbuchung().habenkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label id="sb_betrag">Betrag</label>
    <input type="number" step="0.01" id="b_betrag" data-bind="value: schnellbuchungen().selectedSchnellbuchung().betrag">
    <button data-bind="click: schnellbuchungen().anlegen">Anlegen</button>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i4l.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->

<!-- Formular: Schnellbuchung bearbeiten -->
<div data-role="page" id="schnellbuchung_bearbeiten">
  <div data-role="header" data-position="fixed">
    <a href="#schnellbuchungen_liste" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1 data-bind="text:i4l.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <label for="sb_config_knz">Kurzbezeichnung</label>
    <input type="text" id="sb_config_knz" data-bind="value: schnellbuchungen().selectedSchnellbuchung().config_knz">
    <label for="sb_buchungstext">Buchungstext</label>
    <input type="text" id="sb_buchungstext" data-bind="value: schnellbuchungen().selectedSchnellbuchung().buchungstext">
    <label for="sb_sollkonto">Soll-Konto</label>
    <select id="sb_sollkonto" data-bind="value: schnellbuchungen().selectedSchnellbuchung().sollkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label for="sb_habenkonto">Haben-Konto</label>
    <select id="sb_habenkonto" data-bind="value: schnellbuchungen().selectedSchnellbuchung().habenkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label id="sb_betrag">Betrag</label>
    <input type="number" step="0.01" id="b_betrag" data-bind="value: schnellbuchungen().selectedSchnellbuchung().betrag">
    <button data-bind="click: schnellbuchungen().speichern">Speichern</button>
    <button data-bind="click: schnellbuchungen().loeschen">L&ouml;schen</button>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i4l.general.copy"></h4>
  </div><!-- /footer -->
</div><!-- /page -->
