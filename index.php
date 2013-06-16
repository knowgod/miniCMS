<?php

include 'app.php';

define('LOG_ON', 1);
/**
 * Error reporting
 */
error_reporting(E_ALL | E_STRICT);

$model = app::getModel('page')->load(1);

?>
