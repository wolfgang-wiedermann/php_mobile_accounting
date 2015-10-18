<div data-role="page" data-theme="b" id="verlauf_einfach_view">
    <div data-role="header" data-position="fixed">
        <a href="#ergebnis_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
        <h1 data-bind="text: verlauf().titel"></h1>
    </div><!-- /header -->
    <div data-role="content">
        <table data-bind="foreach: verlauf().verlauf_einfach">
            <tr>
                <td data-bind="text: monat"></td>
                <td>&nbsp;&nbsp;</td>
                <td data-bind="text: betrag" class="td_betrag"></td>
            </tr>
        </table><br/>
        <canvas id="verlauf_einfach_grafik">
        </canvas>
    </div><!-- /content -->
    <div data-role="footer" data-position="fixed">
        <h4 data-bind="text:i18n.general.copy"></h4>
    </div><!-- /footer -->
</div><!-- /page -->