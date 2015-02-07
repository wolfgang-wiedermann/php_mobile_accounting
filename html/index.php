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
<?php define("MAIN_PAGE", 1); ?>
<!DOCTYPE html>
<!--<html lang="de" manifest="manifest.php"> -->
<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/lib/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="./css/hhb.css" />
    <!-- Standard-Bibliotheken -->
    <script src="./js/jquery-2.1.3.min.js"></script>
    <script src="./js/jquery.mobile-1.4.5.min.js"></script>
    <script src="./js/knockout-3.2.0.js"></script>
    <!-- App-spezifische Code-Dateien -->
    <script src="./js/knockout-ext.js"></script>
    <script src="./js/rpc.js"></script>
    <script src="./js/util.js"></script>
    <script src="./js/diagram.js"></script>
    <script src="./js/app.php"></script>
</head>
<body>
  <!-- Menüs -->
  <?php include_once("./forms/navigation/navigation.php"); ?>
  <?php include_once("./forms/navigation/buchen_menue.php"); ?>
  <!-- Masken aus dem Menü "Buchungen" -->
  <?php include_once("./forms/buchen/buchen.php"); ?>
  <?php include_once("./forms/buchen/aktuellste_buchungen.php"); ?>
  <!-- Masken aus dem Menü "Konten" -->
  <?php include_once("./forms/konten/konten_liste.php"); ?>
  <?php include_once("./forms/konten/konten_menue.php"); ?>
  <?php include_once("./forms/konten/konten_form.php"); ?>
  <?php include_once("./forms/konten/konten_buchungen.php"); ?>
  <?php include_once("./forms/konten/konten_monatssalden.php"); ?>
  <!-- Masken aus dem Menü "Administration" -->
  <?php include_once("./forms/schnellbuchungen/schnellbuchungen_liste.php"); ?>
</body>
</html>
