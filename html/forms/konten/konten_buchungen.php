<div data-role="page" data-theme="b" id="konto_buchungen">
  <div data-role="header" data-position="fixed">
    <a href="#konten_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1><span data-bind="text:i18n.konten.konto"></span>: <span data-bind="text: konten().selectedKonto().kontonummer"></span></h1>
  </div><!-- /header -->
  <div data-role="content">
    <div><span data-bind="text:i18n.konten.aktueller_saldo"></span>:  <span data-bind="text: konten().saldo"></span></div>
    <div>
      <select data-bind="value: konten().selectedJahr, options: ergebnis().jahre,
                         optionsText: 'jahr', optionsValue: 'jahr', event:{'change': konten().selectedJahrChanged}">
      </select>
    </div>
    <br/>
    <table data-bind="foreach: konten().buchungen">
      <tr>
        <td data-bind="text:buchungsnummer"></td>
        <td data-bind="text:buchungstext"></td>
        <td style="width:10px"></td>
        <td data-bind="text:gegenkonto"></td>
        <td data-bind="text:betrag" class="td_betrag"></td>
        <td style="width:10px"></td>
        <td data-bind="text:datum_de"></td>
      </tr>
    </table>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
