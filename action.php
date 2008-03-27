<?php

/* This is for a popup box, or an AJAX call
	Don't show the site header/footer
*/

define('SITE_ROOT', dirname(__FILE__));
include SITE_ROOT . '/core/config.inc.php';

ksort($ACTIVE_MODULES); 

//load our modules
MainController::loadModules($ACTIVE_MODULES);

$BaseTemplate = new TemplateSet;

//load the main skin
$settings_file = SKINS_PATH . '/' . CURRENT_SKIN . '.php';
if(file_exists($settings_file))
	include $settings_file;

$BaseTemplate->template_path = SKINS_PATH;

Template::Set('MODULE_NAV_INC', $NAVBAR);
Template::Set('MODULE_HEAD_INC', $HTMLHead);

MainController::RunAllActions();

if($_GET['format'] != 'json')
	echo '<script type="text/javascript>
			EvokeListeners();
		  </script>';
?>