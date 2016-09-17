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

hhb.model.types.ExportModel = function() {
    var self = this;

    self.export_journal = function() {
        var win = window.open("../index.php?controller=office&action=journal&format=csv", "Download");
    };

    self.export_guv = function() {
        var win = window.open("../index.php?controller=office&action=guvmonate&format=csv", "Download");
    };

    self.export_bilanz = function() {
        var win = window.open("../index.php?controller=office&action=bilanzmonate&format=csv", "Download");
    };

    self.export_sqlbackup = function() {
        var win = window.open("../index.php?controller=backup&action=sqlbackup", "Download");
    }
};
