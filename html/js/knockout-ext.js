/*
 * Binding-Handler um Darstellungsprobleme zu beheben
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

/*
 * Binding-Handler um JQuery-Mobile Checkbox verwenden zu können
 * (Quelle: http://stackoverflow.com/questions/19085819/jquery-mobile-and-knockout-checkbox-not-updating-with-viewmodel)
 *
 * Verwendung: data-bind="jqmChecked: nameOfBooleanVariable"
 */
ko.bindingHandlers.jqmChecked = {
    init: ko.bindingHandlers.checked.init,
    update: function (element, valueAccessor) {
        if (ko.bindingHandlers.checked.update) {
            ko.bindingHandlers.checked.update.apply(this, arguments);
        } else {
            ko.utils.unwrapObservable(valueAccessor());
        }

        if ($(element).data("mobile-checkboxradio")) {
            $(element).checkboxradio('refresh');
        }
    }
};
