<div data-role="page" data-theme="b" id="offene_posten">
    <div data-role="header" data-position="fixed">
        <a href="#buchen_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
        <h1 data-bind="text:i18n.buchen.offene_posten"></h1>
    </div><!-- /header -->
    <div data-role="content">
        <h4 data-bind="text:i18n.buchen.offene_posten"></h4>
        <table data-bind="foreach: buchen().buchungen" style="width:100%">
            <tr>
                <td data-bind="text:buchungsnummer"></td>
                <td data-bind="text:buchungstext" colspan="3"></td>
            </tr>
            <tr>
                <td data-bind="text:sollkonto"></td>
                <td data-bind="text:habenkonto"></td>
                <td data-bind="text:betrag" class="td_betrag"></td>
                <td data-bind="text:datum_de" style="padding-left: 10pt;"></td>
            </tr>
            <tr>
                <td colspan="4">
                    <a href="#offene_posten" class="ui-btn"
                       data-bind="click:closePostenShowAusbuchen,
                                  text:$root.i18n.buchen.offenen_posten_schliessen"></a>
                </td>
            </tr>
        </table>
    </div><!-- /content -->
    <div data-role="footer" data-position="fixed">
        <h4 data-bind="text:i18n.general.copy"></h4>
    </div><!-- /footer -->
</div><!-- /page -->
