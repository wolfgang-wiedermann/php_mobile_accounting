<div data-role="page" id="buchungen_erfassen">
  <div data-role="header">
    <a href="#buchen_menue" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1>Buchhaltung</h1>
  </div><!-- /header -->
  <div data-role="content">
    <label for="b_buchungstext">Buchungstext</label>
    <input type="text" id="b_buchungstext" data-bind="value: buchen().selectedBuchung().buchungstext">
    <label for="b_sollkonto">Soll-Konto</label>
    <select id="b_sollkonto" data-bind="value: buchen().selectedBuchung().sollkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label for="b_habenkonto">Haben-Konto</label>
    <select id="b_habenkonto" data-bind="value: buchen().selectedBuchung().habenkonto, options: konten().konten, optionsText: 'tostring', optionsValue: 'kontonummer'">
    </select>
    <label id="b_betrag">Betrag</label>
    <input type="number" step="0.01" id="b_betrag" data-bind="value: buchen().selectedBuchung().betrag">
    <label id="b_buchungsdatum">Buchungsdatum</label>
    <input type="date" id="b_buchungsdatum" data-bind="value: buchen().selectedBuchung().datum">
    <button data-bind="click: buchen().verbuchen">verbuchen</button>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>&copy; by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
