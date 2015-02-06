<div data-role="page" id="hauptmenue">
  <div data-role="header">
    <h1>Buchhaltung</h1>
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
  <div data-role="footer">
    <h4>&copy; by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
