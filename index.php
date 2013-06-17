<?php

include 'app.php';

define('LOG_ON', 1);
/**
 * Error reporting
 */
error_reporting(E_ALL | E_STRICT);

app::run();

?>
