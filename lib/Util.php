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

# 
# Verpacken des Response von Controller-Methoden
#
function wrap_response($obj="", $format="json") {

    #error_log("Objekt ".json_encode($obj));
    #error_log("Format ".$format);

    $response = new Response();
    $response->obj = $obj;
    $response->format = $format;

    return $response;
}

class Response {
    public $obj;
    public $format;
}

?>
