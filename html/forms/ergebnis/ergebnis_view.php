<div data-role="page" id="ergebnis_view">
  <div data-role="header" data-position="fixed">
    <a href="#ergebnis_menue" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1 data-bind="text: ergebnis().titel"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <b data-bind="text: ergebnis().untertitel"></b>
    <table data-bind="foreach: ergebnis().rechnung">
      <tr>
        <td data-bind="text: kontonummer"></td>
        <td>&nbsp;&nbsp;</td>
        <td data-bind="text: bezeichnung"></td>
        <td>&nbsp;&nbsp;</td>
        <td data-bind="text: betrag" class="td_betrag"></td>
      </tr>
    </table><br/>
    <table data-bind="foreach: ergebnis().summen">
      <tr>
        <td data-bind="text: label"></td>
        <td>&nbsp;&nbsp;</td>
        <td data-bind="text: betrag" class="td_betrag"></td>
      </tr>
    </table>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4>&copy; 2013-2015 by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
