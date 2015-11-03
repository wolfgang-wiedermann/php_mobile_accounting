<div data-role="page" data-theme="b" id="configuration_form">
    <div data-role="header" data-position="fixed">
        <a href="#configuration_liste" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
        <h1 data-bind="text:i18n.general.buchhaltung"></h1>
    </div><!-- /header -->
    <div data-role="content">
        <label for="c_param_knz">Parameter_KNZ</label>
        <input type="text" data-bind="value: configuration().selectedConfigurationParam().param_knz" disabled>
        <label for="c_param_desc">Beschreibung</label>
        <input type="text" data-bind="value: configuration().selectedConfigurationParam().param_desc">
        <label for="c_param_desc">Wert</label>
        <input type="text" data-bind="value: configuration().selectedConfigurationParam().param_value">
        <button data-bind="click:configuration().updateConfigurationParam">Speichern</button>
    </div><!-- /content -->
    <div data-role="footer" data-position="fixed">
        <h4 data-bind="text:i18n.general.copy"></h4>
    </div><!-- /footer -->
</div><!-- /page -->