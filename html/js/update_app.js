/*
* HTML5-Update Handling implementation
* (c) 2014 by Wolfgang Wiedermann
*/
var de = de || {};
de.ww = de.ww || {};
de.ww.updater = de.ww.updater || {};

de.ww.updater.handlers = (function() { 
    var self = this;

    registerHandler = function(eventname, handler) {
       window.applicationCache.addEventListener(eventname, handler);
    };

    return {  
        /*
         * The event checking is the base for deciding whether 
         * there is a new version available to be installed
         */
	onCheck : function(checkHandler) {
            self.registerHandler('checking', function() {
                checkHandler();
            });
        },

        /*
         * Can be used to register handlers for the new version event.
         * It enforces an automatic reload of the page to ensure the updates
         * being activated in the current view.
         */
        onUpdateReady : function(newVersionHandler) {
            self.registerHandler('updateready', function() {
                if(window.applicationCache.status === window.applicationCache.UPDATEREADY) {
                    newVersionHandler();
                    location.reload();
                }
            });
        }
    }; 
})();
