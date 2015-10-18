<div data-role="page" data-theme="b" id="buchen_menue">
  <div data-role="header" data-position="fixed">
    <a href="#hauptmenue" data-role="button" data-icon="home" data-bind="text:i18n.general.home"></a>
    <h1 data-bind="text:i18n.general.buchhaltung"></h1>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview" data-filter="false" data-bind="foreach: navigation().buchungsmenue">
      <li data-theme="c"><a data-bind="text: label, click: callback"></a></li>
    </ul>
  </div><!-- /content -->
  <div data-role="footer" data-position="fixed">
    <h4 data-bind="text:i18n.general.copy"></h4>
  </div><!-- /footer --> 
</div><!-- /page -->
