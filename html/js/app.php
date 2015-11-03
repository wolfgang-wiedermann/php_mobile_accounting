<?php 
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
?>
<?php
// Content-Type auf application/javascript setzen
header('Content-Type: application/javascript');
// Sprachdatei einbetten
include_once("../i18n/lang-de.js");
// Laden und zusammenfassen aller Javascript-Code-Dateien aus den Formularen
include_once("../forms/navigation/navigation_model.js");
include_once("../forms/schnellbuchungen/schnellbuchungen_model.js");
include_once("../forms/konten/kontenarten_model.js");
include_once("../forms/konten/konten_model.js");
include_once("../forms/buchen/buchen_model.js");
include_once("../forms/ergebnis/ergebnis_model.js");
include_once("../forms/verlauf/verlauf_model.js");
include_once("../forms/export/export_model.js");
include_once("../forms/configuration/configuration_model.js");

// MainModel importieren
include_once("./main_model.js");
?>
