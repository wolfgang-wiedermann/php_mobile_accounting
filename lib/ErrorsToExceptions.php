<?php

// von http://codeissue.com/articles/a04e000c369b101/centralized-error-exception-handling-in-php
//Error handling
function ErrorHandler($errLevel, $errMsg, $errFile, $errLine)
{
    throw new ErrorException($errMsg, $errLevel, $errLevel, $errFile, $errLine);
}

set_error_handler("ErrorHandler");

?>
