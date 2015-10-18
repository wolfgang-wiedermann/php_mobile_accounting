<div data-role="page" data-theme="b" id="hauptmenue">
  <div data-role="header" data-position="fixed">
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <ul id="h_navigation" data-role="listview" data-inset="false" data-filter="true">
      <li data-role="list-divider" data-bind="text:i18n.navigation.basisfunktionen"></li>
      <!-- ko foreach: navigation().basisfunktionen -->
      <li data-theme="c"><a data-bind="text: label, click:callback"></a></li>
      <!-- /ko -->
      <li data-role="list-divider" data-bind="text:i18n.navigation.administration"></li>
      <!-- ko foreach: navigation().administration -->
      <li data-theme="c"><a data-bind="text: label, click: callback"></a></li>
      <!-- /ko -->
      <li data-role="list-divider" data-bind="text:i18n.navigation.schnellbuchungen"></li>
      <!-- ko foreach: schnellbuchungen().schnellbuchungen_navigation -->
      <li data-theme="c"><a data-bind="text:label, click: callback"></a></li>
      <!-- /ko -->
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
