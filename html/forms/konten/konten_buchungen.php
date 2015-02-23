<div data-role="page" id="konto_buchungen">
  <div data-role="header" data-position="fixed">
    <a href="#konten_menue" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1>Konto: <span data-bind="text: konten().selectedKonto().kontonummer"></span></h1>
  </div><!-- /header -->
  <div data-role="content">
    <div>aktueller Saldo: <span data-bind="text: konten().saldo"></span></div>
    <br/>
    <table data-bind="foreach: konten().buchungen">
      <tr>
        <td data-bind="text:buchungsnummer"></td>
        <td data-bind="text:buchungstext" colspan="3"></td>
      </tr>
      <tr>
        <td data-bind="text:gegenkonto"></td>
        <td data-bind="text:betrag" class="td_betrag"></td>
        <td style="width:10px"></td>
        <td data-bind="text:datum_de"></td>
      </tr>
    </table>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i4l.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
