<!-- Formular: Schnellbuchung anlegen -->
<div data-role="page" data-theme="b" id="schnellbuchung_anlegen">
  <div data-role="header" data-position="fixed">
    <a href="#schnellbuchungen_liste" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <label for="sb_config_knz" data-bind="text:i18n.schnellbuchungen.kurzbezeichnung"></label>
    <input type="text" id="sb_config_knz" data-bind="value: schnellbuchungen().selectedSchnellbuchung().config_knz">
    <label for="sb_buchungstext" data-bind="text:i18n.schnellbuchungen.buchungstext"></label>
    <input type="text" id="sb_buchungstext" data-bind="value: schnellbuchungen().selectedSchnellbuchung().buchungstext">
    <label for="sb_sollkonto" data-bind="text:i18n.buchen.sollkonto"></label>
    <select id="sb_sollkonto" data-bind="value: schnellbuchungen().selectedSchnellbuchung().sollkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label for="sb_habenkonto" data-bind="text:i18n.buchen.habenkonto"></label>
    <select id="sb_habenkonto" data-bind="value: schnellbuchungen().selectedSchnellbuchung().habenkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label id="sb_betrag" data-bind="text:i18n.buchen.betrag"></label>
    <input type="number" step="0.01" id="b_betrag" data-bind="value: schnellbuchungen().selectedSchnellbuchung().betrag">
    <button data-bind="click: schnellbuchungen().anlegen, text:i18n.schnellbuchungen.anlegen"></button>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->

<!-- Formular: Schnellbuchung bearbeiten -->
<div data-role="page" data-theme="b" id="schnellbuchung_bearbeiten">
  <div data-role="header" data-position="fixed">
    <a href="#schnellbuchungen_liste" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <label for="sb_config_knz" data-bind="text:i18n.schnellbuchungen.kurzbezeichnung"></label>
    <input type="text" id="sb_config_knz" data-bind="value: schnellbuchungen().selectedSchnellbuchung().config_knz">
    <label for="sb_buchungstext" data-bind="text:i18n.schnellbuchungen.buchungstext"></label>
    <input type="text" id="sb_buchungstext" data-bind="value: schnellbuchungen().selectedSchnellbuchung().buchungstext">
    <label for="sb_sollkonto" data-bind="text:i18n.buchen.sollkonto"></label>
    <select id="sb_sollkonto" data-bind="value: schnellbuchungen().selectedSchnellbuchung().sollkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label for="sb_habenkonto" data-bind="text:i18n.buchen.habenkonto"></label>
    <select id="sb_habenkonto" data-bind="value: schnellbuchungen().selectedSchnellbuchung().habenkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label id="sb_betrag" data-bind="text:i18n.buchen.betrag"></label>
    <input type="number" step="0.01" id="b_betrag" data-bind="value: schnellbuchungen().selectedSchnellbuchung().betrag">
    <button data-bind="click: schnellbuchungen().speichern, text:i18n.konten.speichern_button"></button>
    <button data-bind="click: schnellbuchungen().loeschen, text:i18n.schnellbuchungen.loeschen"></button>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer -->
</div><!-- /page -->
