<div data-role="page" data-theme="b" id="configuration_liste">
    <div data-role="header" data-position="fixed">
        <a href="#hauptmenue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
        <h1 data-bind="text:i18n.general.buchhaltung"></h1>
    </div><!-- /header -->
    <div data-role="content">
        <ul data-role="listview" data-inset="false" data-filter="true" id="s_liste">
            <!-- ko foreach: configuration().configuration -->
            <li data-theme="c"><a data-bind="text: description, click: $root.configuration().open"></a></li>
            <!-- /ko -->
        </ul>
    </div><!-- /content -->
    <div data-role="footer" data-position="fixed">
        <h4 data-bind="text:i18n.general.copy"></h4>
    </div><!-- /footer -->
</div><!-- /page -->
