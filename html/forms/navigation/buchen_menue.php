<div data-role="page" id="buchen_menue">
  <div data-role="header">
    <a href="#hauptmenue" data-role="button" data-icon="home">Men&uuml;</a>
    <h1>Buchhaltung</h1>
  </div><!-- /header -->
  <div data-role="content">
    <ul data-role="listview" data-filter="false" data-bind="foreach: navigation().buchungsmenue">
      <li><a data-bind="text: label, click: callback"></a></li>
    </ul>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>&copy; by Wolfgang Wiedermann</h4>
  </div><!-- /footer --> 
</div><!-- /page -->
