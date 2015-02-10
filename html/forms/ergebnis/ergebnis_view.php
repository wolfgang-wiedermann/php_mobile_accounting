<div data-role="page" id="ergebnis_view">
  <div data-role="header" data-position="fixed">
    <a href="#ergebnis_menue" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1 data-bind="text: ergebnis().titel"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <b>Monatssalden</b>
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
    <h4>&copy; 2013-2015 by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
