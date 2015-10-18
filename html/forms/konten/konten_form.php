<!-- Vorlage des Konto-Formulars -->
<script type="text/html" id="konto_form_template">
  <label for="k_kontonummer" data-bind="text:$root.i18n.konten.konto_nummer"></label>
  <input type="text" id="k_kontonummer" data-bind="value: kontonummer">
  <label for="k_bezeichnung" data-bind="text:$root.i18n.konten.konto_bezeichnung"></label>
  <input type="text" id="k_bezeichnung" data-bind="value: bezeichnung">
  <label for="k_kontenart" data-bind="text:$root.i18n.konten.konto_art"></label>
  <select id="k_kontenart" data-bind="value: kontenart_id, options: $root.kontenarten,
    optionsValue: 'kontenart_id', optionsText: 'bezeichnung'"></select>
</script>

<!-- Konto anlegen -->
<div data-role="page" data-theme="b" id="konto_neu">
  <div data-role="header" data-position="fixed">
    <a href="#konten_liste" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.konten.anlegen"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <div data-bind="template: { name: 'konto_form_template', data: konten().selectedKonto() }"></div>
    <button data-bind="click: konten().anlegen, text:i18n.konten.anlegen_button"></button>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->

<!-- Konto bearbeiten -->
<div data-role="page" data-theme="b" id="konto_bearbeiten">
  <div data-role="header" data-position="fixed">
    <a href="#konten_menue" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1 data-bind="text:i18n.konten.konto_bearbeiten"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <div data-bind="template: { name: 'konto_form_template', data: konten().selectedKonto() }"></div>
    <button data-bind="click: konten().speichern, text:i18n.konten.speichern_button"></button>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer -->
</div><!-- /page -->