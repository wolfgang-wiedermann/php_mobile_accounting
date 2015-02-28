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

// Eintrag für die Verlaufsdarstellung mit einer Kurve
hhb.model.types.VerlaufEintragEinfach = function(data) {
    var self = this;

    self.monat = ko.observable("000000");
    self.betrag = ko.observable(0);

    if(!!data) {
        self.monat(data.grouping);
        self.betrag(data.saldo);
    }
};

// Eintrag für die Verlaufsdarstellung mit mehreren Kurven
hhb.model.types.VerlaufEintragMehrfach = function(data) {
    var self = this;

    self.monat = ko.observable("000000");
    self.betraege = ko.observableArray([0, 0]);

    if(!!data) {
        self.monat(data.tag);
        self.betraege.removeAll();
        self.betraege.push(data.vormonat);
        self.betraege.push(data.aktuell);
    }
};

// Zentraler Model-Knoten für die Verlaufsauswertungen
hhb.model.types.VerlaufModel = function() {
    var self = this;

    self.titel = ko.observable(hhb.i18n.verlauf.verlaufsauswertung);
    self.selected_monat = ko.observable("000000");
    self.sollhaben = ko.observable("H");

    self.verlauf_einfach = ko.observableArray([]);
    self.verlauf_einfach.push(new hhb.model.types.VerlaufEintragEinfach());

    self.verlauf_mehrfach = ko.observableArray([]);
    self.verlauf_mehrfach.push(new hhb.model.types.VerlaufEintragMehrfach());

    self.konten_selectable = ko.observableArray([]);
    self.konten_selected = ko.observableArray([]);

    self.onchange = null;

    self.verlaufaufwand = function() {
        self.titel(hhb.i18n.verlauf.verlaufaufwand_title);
        self.verlauf_einfach.removeAll();
        self.loadVerlaufEinfach('ergebnis', 'verlauf', {'id': 3});
    };

    self.verlaufertrag = function() {
        self.titel(hhb.i18n.verlauf.verlaufertrag_title);
        self.verlauf_einfach.removeAll();
        self.loadVerlaufEinfach('ergebnis', 'verlauf', {'id': 4});
    };

    self.verlaufgewinn = function() {
        self.titel(hhb.i18n.verlauf.verlaufgewinn_title);
        self.verlauf_einfach.removeAll();
        self.loadVerlaufEinfach('ergebnis', 'verlauf_gewinn', {});
    };

    self.verlaufintern = function() {
        self.titel(hhb.i18n.verlauf.monatsinternerverlauf_title);
        self.verlauf_mehrfach.removeAll();
        self.loadVerlaufMehrfach('verlauf', 'intramonth', {'month_id': self.selected_monat()});
        self.onchange = self.verlaufintern;
    };

    self.verlaufzuundabfluss = function() {
        self.titel(hhb.i18n.verlauf.zuundabfluss_title);
        jQuery.mobile.changePage('#verlauf_kontenliste');
    };

    self.showVerlaufzuundabfluss = function(konto) {
        self.verlauf_einfach.removeAll();
        if(self.sollhaben() == 'S') {
            self.titel(hhb.i18n.verlauf.zufluss+': '+konto.kontonummer());
        } else {
            self.titel(hhb.i18n.verlauf.abfluss+': '+konto.kontonummer());
        }
        self.loadVerlaufEinfach('verlauf', 'cashflow', {'id': konto.kontonummer(), 'side': self.sollhaben()});
    };

    self.verlauffrei = function() {
        self.konten_selected.removeAll();
        self.konten_selectable.removeAll();
        hhb.model.MainModel.konten().refreshKonten(function(kontenModel) {
             for(var i = 0; i < kontenModel.konten().length; i++) {
                 self.konten_selectable.push(kontenModel.konten()[i]);
             }
            jQuery.mobile.changePage("#verlauf_kontenauswahl");
            $(".konten_liste2").listview();
            $(".konten_liste2").listview("refresh");
        });
    };

    self.verlauffrei_select = function(konto) {
        if(self.konten_selected().length == 0) {
            self.konten_selected_type = self.getKontenFamily(konto.kontenart_id());
        }
        if(self.konten_selected_type === self.getKontenFamily(konto.kontenart_id())) {
            self.konten_selected.push(konto);
            self.konten_selectable.remove(konto);
            $(".konten_liste2").listview();
            $(".konten_liste2").listview("refresh");
        } else {
            alert(hhb.i18n.verlauf.selektionsfehler);
        }
    };

    self.verlauffrei_deselect = function(konto) {
        self.konten_selectable.push(konto);
        self.konten_selected.remove(konto);
        $(".konten_liste2").listview();
        $(".konten_liste2").listview("refresh");
    };

    self.showVerlauffrei = function() {
        var kontoNumbers = "";
        var length = self.konten_selected().length;
        self.verlauf_einfach.removeAll();
        
        for(var i = 0; i < length; i++) {
            kontoNumbers += self.konten_selected()[i].kontonummer();
            if(i < length-1) {
                kontoNumbers += ",";
            }
        }
        self.loadVerlaufEinfach('verlauf', 'monatssalden', {'id':kontoNumbers});
    };

    self.loadVerlaufEinfach = function(controller, action, parameters) {
        doGETwithCache(controller, action, parameters,
            function(data) {
                var diagramData = [];

                for(var key in data) {
                    var line = data[key];
                    self.verlauf_einfach.push(new hhb.model.types.VerlaufEintragEinfach(line));
                    diagramData.push(line.saldo);

                    d.init("verlauf_einfach_grafik");
                    d.setToWindowWidth();
                    d.drawLineDiagramFor(diagramData);
                }
                jQuery.mobile.changePage('#verlauf_einfach_view');
            },
            function(error) {
                util.showErrorMessage(error);
            }
        );
    };

    self.loadVerlaufMehrfach = function(controller, action, parameters) {
        doGETwithCache(controller, action, parameters,
            function(data) {
                var diagramData = [];
                diagramData[0] = [];
                diagramData[1] = [];

                for(var key in data) {
                    var line = data[key];
                    self.verlauf_mehrfach.push(new hhb.model.types.VerlaufEintragMehrfach(line));
                    diagramData[0].push(line.vormonat);
                    diagramData[1].push(line.aktuell);

                    d.init("verlauf_mehrfach_grafik");
                    d.setToWindowWidth();
                    d.drawMultiLineDiagramFor(diagramData, false);
                }
                jQuery.mobile.changePage('#verlauf_mehrfach_view');
            },
            function(error) {
                util.showErrorMessage(error);
            }
        );
    };


    self.getKontenFamily = function(kontenart_id) {
        if(kontenart_id === '1' || kontenart_id === '2') {
            return 1;
        } else if(kontenart_id === '3' || kontenart_id === '4') {
            return 2;
        } else {
            return 0;
        }
    };
};