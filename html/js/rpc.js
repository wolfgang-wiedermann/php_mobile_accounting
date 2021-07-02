/**
* Funktion zum Absetzen eines GET-Requests
* @param controller = Bezeichnung des Controllers als String (wie in URL)
* @param action = Bezeichnung der Action als String
* @param parameters = Parameter als assoziatives Array Key=>Value
* @param successHandler = Funktions-Handle für Erfolgsfall
* @param errorHandler = Funktions-Handle für Fehlerfall
*/
function doGET(controller, action, parameters, successHandler, errorHandler) {
    var additionalParams = "";
    for(var key in parameters) {
        additionalParams += "&"+key+"="+parameters[key];
    }
    $.ajax({
        type: 'GET',
        url: "../index.php?controller="+controller+"&action="+action+additionalParams,
        dataType:"json",
    }).done(function(data) {
        successHandler(data);
    }).fail(function(error) {
        if(error.status >= 299) {
            errorHandler(error);
        }
    });
}

/**
* Funktion zum Absetzen eines POST-Requests
* @param controller = Bezeichnung des Controllers als String (wie in URL)
* @param action = Bezeichnung der Action als String
* @param parameterObject = Parameter als JSON-Objekt (String)
* @param successHandler = Funktions-Handle für Erfolgsfall
* @param errorHandler = Funktions-Handle für Fehlerfall
*/
function doPOST(controller, action, parameterObject, successHandler, errorHandler) {
    $.ajax({
        type: 'POST',
        url: "../index.php?controller="+controller+"&action="+action,
        dataType:"json",
        contentType:"application/json",
        data: parameterObject,
    }).done(function(data) {
        successHandler(data);
    }).fail(function(error) {
        if(error.status >= 299) {
            errorHandler(error);
        }
    });
}

