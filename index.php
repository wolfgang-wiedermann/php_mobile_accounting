<?php
/*
 * Copyright (c) 2013 by Wolfgang Wiedermann
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

if(!isset($_REQUEST['controller'])) {
   # Nicht-Ajax-Zugriffe nach ./html/index.php weiterleiten
   $hostname = $_SERVER['HTTP_HOST'];
   $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
   $url = "http://".$hostname.$path."/html/index.php";
   header("Location: $url");
   exit;
} else {
   # Datenbank-Helper laden
   require_once("./lib/Util.php");
   require_once("./lib/Database.php");
   require_once("./lib/QueryHandler.php");
   # So umstellen das Errors als Exceptions geliefert werden
   include_once("./lib/ErrorsToExceptions.php");
   # Einstiegspunkt in das Framework
   require_once("./lib/Dispatcher.php");
   
   if(isset($_SERVER['REMOTE_USER'])) {
       $disp = new Dispatcher();
       $user = $_SERVER['REMOTE_USER'];
       $disp->setRemoteUser($user);
       echo $disp->invoke($_REQUEST);
   } else {
       throw new Exception("Fehler: Benutzer nicht über \$_SERVER['REMOTE_USER'] ermittelbar"); 
   }
}
?>
