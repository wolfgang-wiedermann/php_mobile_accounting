// -- <?php
/*
 * Copyright (c) 2015 by Wolfgang Wiedermann
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; version 3 of the
 * License.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 * USA
 */
//?>

var hhb = hhb || {};
hhb.model = hhb.model || {};
hhb.model.types = hhb.model.types || {};

/*
 * Datenmodell einer Kontenart
 */
hhb.model.types.Kontenart = function(config) {
    var self = this;
    self.kontenart_id = ko.observable(0);
    self.bezeichnung = ko.observable("");

    if(!!config) {
        self.kontenart_id(config.kontenart_id);
        self.bezeichnung(config.bezeichnung);
    }
};

/*
* Statische Methode zum laden der Kontenarten
*/
hhb.model.types.Kontenart.load = function(observableArray) {
    doGET('kontenart', 'list', [],
        function(data) {
            for(var i = 0; i < data.length; i++) {
                observableArray.push(new hhb.model.types.Kontenart(data[i]));
            }
        },
        function(error) {
            util.showErrorMessage(error, "Fehler beim Laden der Kontenarten aufgetreten");
        }
    )
};