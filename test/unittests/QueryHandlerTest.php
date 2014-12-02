<?php

include_once("lib/QueryHandler.php");

class QueryHandlerTest extends PHPUnit_Framework_TestCase {

    function testIsValidString() {
        $handler = new QueryHandler("guv_monat.sql");
        $success = $handler->isValidString("abcdeFgHi123+-;");
        $this->assertEquals($success, true, "Korrekter String wurde als fehlerhaft erkannt");
        $error = $handler->isValidString("ab'1234");
        $this->assertEquals($error, false, "Fehlerhafter String wurde nicht als fehlerhaft erkannt");
    }

    function testIsValidNumber() {
        $handler = new QueryHandler("guv_monat.sql");
        $success = $handler->isValidNumber("1234567890,00");
        $this->assertEquals($success, true, "Korrekte Zahl wurde als fehlerhaft erkannt");
        $success = $handler->isValidNumber("1234567890.00");
        $this->assertEquals($success, true, "Korrekte Zahl wurde als fehlerhaft erkannt");
        $error = $handler->isValidNumber("123456a7890,00");
        $this->assertEquals(!$error, true, "Fehlerhafte Zahl wurde nicht als fehlerhaft erkannt");
    }

}
 