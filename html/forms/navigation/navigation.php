<div data-role="page" id="hauptmenue">
  <div data-role="header" data-position="fixed">
    <h1 data-bind="text:i4l.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <ul id="h_navigation" data-role="listview" data-inset="false" data-filter="true">
      <li data-role="list-divider">Basisfunktionen</li>
      <!-- ko foreach: navigation().basisfunktionen -->
      <li><a data-bind="text: label, click:callback"></a></li>
      <!-- /ko -->
      <li data-role="list-divider">Administration</li>
      <!-- ko foreach: navigation().administration -->
      <li><a data-bind="text: label, click: callback"></a></li>
      <!-- /ko -->
      <li data-role="list-divider">Schnellbuchungen</li>
      <!-- ko foreach: schnellbuchungen().schnellbuchungen_navigation -->
      <li><a data-bind="text:label, click: callback"></a></li>
      <!-- /ko -->
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i4l.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
