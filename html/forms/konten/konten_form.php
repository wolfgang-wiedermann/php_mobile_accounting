<!-- Vorlage des Konto-Formulars -->
<script type="text/html" id="konto_form_template">
  <label for="k_kontonummer">Kontonummer</label>
  <input type="text" id="k_kontonummer" data-bind="value: kontonummer">
  <label for="k_bezeichnung">Bezeichnung</label>
  <input type="text" id="k_bezeichnung" data-bind="value: bezeichnung">
</script>
<!-- Konto anlegen -->
<div data-role="page" id="konto_neu">
  <div data-role="header">
    <a href="#konten_liste" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1>Buchhaltung</h1>
  </div><!-- /header -->
  <div data-role="content">
    <h4>Konto anlegen</h4>
    <div data-bind="template: { name: 'konto_form_template', data: konten().selectedKonto() }"></div>
    <button data-bind="click: konten().anlegen">Anlegen</button>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>&copy; by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
<!-- Konto bearbeiten -->
<div data-role="page" id="konto_bearbeiten">
  <div data-role="header">
    <a href="#konten_menue" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1>Buchhaltung</h1>
  </div><!-- /header -->
  <div data-role="content">
    <h4>Konto bearbeiten</h4>
    <div data-bind="template: { name: 'konto_form_template', data: konten().selectedKonto() }"></div>
    <button data-bind="click: konten().speichern">Speichern</button>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>&copy; by Wolfgang Wiedermann</h4>
  </div><!-- /footer -->
</div><!-- /page -->
