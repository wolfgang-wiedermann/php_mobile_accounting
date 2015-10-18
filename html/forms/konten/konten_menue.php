<div data-role="page" data-theme="b" id="konten_menue">
  <div data-role="header" data-position="fixed">
    <a href="#konten_liste" data-role="button" data-icon="home" data-bind="text:i18n.general.back"></a>
    <h1><span data-bind="text:i18n.konten.konto"></span>: <span data-bind="text: konten().selectedKonto().kontonummer"></span></h1>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview">
      <li data-theme="c"><a href="#konto_monatssalden" data-bind="click: $root.konten().openMonatssalden, text:i18n.konten.monatssalden"></a></li>
      <li data-theme="c"><a href="#konto_buchungen" data-bind="click: $root.konten().openBuchungen, text:i18n.konten.buchungen_anzeigen"></a></li>
      <li data-theme="c"><a href="#konto_bearbeiten" data-bind="click: $root.konten().openKontenBearbeiten, text:i18n.konten.konto_bearbeiten"></a></li>
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
