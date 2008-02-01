<?php

/* This is for a popup box, or an AJAX call
	Don't show the site header/footer
*/

define('SITE_ROOT', dirname(__FILE__));
include SITE_ROOT . '/core/config.inc.php';

MainController::RunAllActions();

?>