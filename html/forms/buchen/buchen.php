<div data-role="page" data-theme="b" id="buchungen_erfassen">
  <div data-role="header" data-position="fixed">
    <a href="#buchen_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <label for="b_buchungstext" data-bind="text:i18n.buchen.buchungstext"></label>
    <input type="text" id="b_buchungstext" data-bind="value: buchen().selectedBuchung().buchungstext">
    <label for="b_sollkonto" data-bind="text:i18n.buchen.sollkonto"></label>
    <select id="b_sollkonto" data-bind="value: buchen().selectedBuchung().sollkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label for="b_habenkonto" data-bind="text:i18n.buchen.habenkonto"></label>
    <select id="b_habenkonto" data-bind="value: buchen().selectedBuchung().habenkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label id="b_betrag" data-bind="text:i18n.buchen.betrag"></label>
    <input type="number" step="0.01" id="b_betrag" data-bind="value: buchen().selectedBuchung().betrag">
    <label id="b_buchungsdatum" data-bind="text:i18n.buchen.buchungsdatum"></label>
    <input type="date" id="b_buchungsdatum" data-bind="value: buchen().selectedBuchung().datum">
    <label><input type="checkbox" data-bind="jqmChecked: buchen().selectedBuchung().is_offener_posten"> offener Posten</label>
    <button data-bind="click: buchen().verbuchen, text:i18n.buchen.verbuchen"></button>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
