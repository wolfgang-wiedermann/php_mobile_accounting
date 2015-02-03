/*
 * Bindling-Handler um Darstellungsprobleme zu beheben
 * (Quelle: http://stackoverflow.com/questions/15702996/jquery-mobile-with-knockoutjs-listview-issue)
 * 
 * Verwendung : data-bind="..., jqmRefreshList: nameoflistmodel"
 */
 ko.bindingHandlers.jqmRefreshList = {
     update: function (element, valueAccessor) {
         ko.utils.unwrapObservable(valueAccessor()); // make this update fire each time the array is updated.
         $(element).listview("refresh")
     }
 };

