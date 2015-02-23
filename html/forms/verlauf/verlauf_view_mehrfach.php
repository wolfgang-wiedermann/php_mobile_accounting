<div data-role="page" id="verlauf_mehrfach_view">
    <div data-role="header" data-position="fixed">
        <a href="#ergebnis_menue" data-role="button" data-icon="home">Zur&uuml;ck</a>
        <h1 data-bind="text: verlauf().titel"></h1>
    </div><!-- /header -->
    <div data-role="content">
        <div id="monat_selection_view">
            <select data-bind="value: verlauf().selected_monat, options: ergebnis().monate,
                         optionsText: 'monat', optionsValue: 'monat', event:{'change': verlauf().onchange}">
            </select>
        </div>
        <table>
            <tr>
                <td>Tag</td>
                <td>&nbsp;&nbsp;</td>
                <td>Vormonat</td>
                <td>&nbsp;&nbsp;</td>
                <td>Aktuell</td>
            </tr>
            <!-- ko foreach: verlauf().verlauf_mehrfach -->
            <tr>
                <td data-bind="text: monat"></td>
                <!-- ko foreach: betraege -->
                <td>&nbsp;&nbsp;</td>
                <td data-bind="text: $data" class="td_betrag"></td>
                <!-- /ko -->
            </tr>
            <!-- /ko -->
        </table><br/>
        <canvas id="verlauf_mehrfach_grafik">
        </canvas>
    </div><!-- /content -->
    <div data-role="footer" data-position="fixed">
        <h4>&copy; 2013-2015 by Wolfgang Wiedermann</h4>
    </div><!-- /footer -->
</div><!-- /page -->