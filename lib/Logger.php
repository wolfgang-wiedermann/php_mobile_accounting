<?php

function logX($what) {
    if(defined("DEBUG")) {
        echo "DEBUG: ".$what."\n";
    } else {
#        error_log($what);
    }
}

?>
