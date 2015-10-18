<div data-role="page" data-theme="b" id="konto_monatssalden">
  <div data-role="header" data-position="fixed">
    <a href="#konten_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1><span data-bind="text:i18n.konten.konto"></span>: <span data-bind="text: konten().selectedKonto().kontonummer"></span></h1>
  </div><!-- /header -->
  <div data-role="content">
    <b data-bind="text:i18n.konten.monatssalden"></b>
    <table data-bind="foreach: konten().salden">
      <tr>
        <td data-bind="text: grouping"></td>
        <td> : </td>
        <td data-bind="text: saldo" class="td_betrag"></td>
      </tr>
    </table>
    <canvas id="konto_monatssalden_grafik" style="width:100%; height:300px;"></canvas>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
