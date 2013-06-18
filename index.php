<?php
/**
 * Include application main class.
 * There the autoloader is defined.
 */
include 'app.php';

/**
 * Remove this line if you want logging disabled
 */
define('LOG_ON', 1);
/**
 * Error reporting
 */
error_reporting(E_ALL | E_STRICT);

/**
 * Execute application
 */
app::run();
?>
