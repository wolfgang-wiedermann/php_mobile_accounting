<div data-role="page" id="konten_liste">
  <div data-role="header" data-position="fixed">
    <a href="#hauptmenue" data-role="button" data-icon="home">Zur&uuml;ck</a>
    <h1 data-bind="text:i4l.general.buchhaltung"></h1>
    <a href="#konto_neu" data-role="button" data-icon="plus">Konto anlegen</a>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview" data-inset="false" data-filter="true" class="konten_liste">
      <!-- ko foreach: konten().konten -->
      <li><a data-bind="text: tostring, click: $root.konten().openKontenMenu"></a></li>
      <!-- /ko -->
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i4l.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
