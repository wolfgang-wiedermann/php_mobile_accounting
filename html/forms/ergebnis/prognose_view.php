<div data-role="page" data-theme="b" id="prognose_view">
  <div data-role="header" data-position="fixed">
    <a href="#ergebnis_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text: ergebnis().titel"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <b data-bind="text: ergebnis().untertitel"></b>
    <table>
      <tr>
        <td colspan="4" data-bind="text:i18n.ergebnis.konto"></td>
        <td data-bind="text:i18n.ergebnis.vormonat">Vormonat</td>
        <td></td>
        <td data-bind="text:i18n.ergebnis.aktuell">Aktuell</td>
        <td></td>
        <td data-bind="text:i18n.ergebnis.differenz">Differenz</td>
      </tr>
      <!-- ko foreach: ergebnis().prognose -->
      <tr>
        <td data-bind="text: kontonummer"></td>
        <td>&nbsp;&nbsp;</td>
        <td data-bind="text: bezeichnung"></td>
        <td>&nbsp;&nbsp;</td>
        <td data-bind="text: betrag_vormonat" class="td_betrag"></td>
        <td>&nbsp;&nbsp;</td>
        <td data-bind="text: betrag_aktuell" class="td_betrag"></td>
        <td>&nbsp;&nbsp;</td>
        <td data-bind="text: differenz" class="td_betrag"></td>
      </tr>
      <!-- /ko -->
    </table><br/>
    <b>Summen</b><br/>
    <table data-bind="foreach: ergebnis().prognose_summen">
      <tr>
        <td data-bind="text: bezeichnung"></td>
        <td>&nbsp;&nbsp;</td>
        <td data-bind="text: monat" class="td_betrag"></td>
        <td>&nbsp;&nbsp;</td>
        <td data-bind="text: summe" class="td_betrag"></td>
      </tr>
    </table>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
