<?php

class TestController {
function invoke($action, $request, $user) {
    logX("TestController invoked with: $action, $user");
    $test = array();
    $test['name'] = "Mustermann";
    $test['vorname'] = "Max";
    return $test;
}
}

?>
