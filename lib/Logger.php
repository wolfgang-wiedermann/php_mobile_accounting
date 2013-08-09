<?php

function logX($what) {
    if(defined("DEBUG")) {
        echo "DEBUG: ".$what."\n";
    } else {
#        syslog($what);
    }
}

?>
