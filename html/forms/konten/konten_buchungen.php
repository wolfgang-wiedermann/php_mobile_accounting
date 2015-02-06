<div data-role="page" id="konto_buchungen">
  <div data-role="header">
    <a href="#konten_menue" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1>Konto: <span data-bind="text: konten().selectedKonto().kontonummer"></span></h1>
  </div><!-- /header -->
  <div data-role="content">
    <div>aktueller Saldo: <span data-bind="text: konten().saldo"></span></div>
    <br/>
    <table data-bind="foreach: konten().buchungen">
      <tr>
        <td data-bind="text:buchungsnummer"></td>
        <td data-bind="text:buchungstext" colspan="2"></td>
      </tr>
      <tr>
        <td data-bind="text:gegenkonto"></td>
        <td data-bind="text:betrag" class="td_betrag"></td>
        <td data-bind="text:datum_de"></td>
      </tr>
    </table>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>&copy; by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
