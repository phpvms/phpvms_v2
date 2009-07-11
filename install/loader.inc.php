<?php
/**
 * phpVMS Installer File
 *  "Boot" file includes our basic "needs" for the installer
 */

define('SITE_ROOT', str_replace('install', '', dirname(__FILE__)));
define('CORE_PATH', SITE_ROOT . 'core/');
define('CLASS_PATH', CORE_PATH . 'classes/');


if(!file_exists(CORE_PATH.'/local.config.php') || filesize(CORE_PATH.'/local.config.php') == 0)
{
	/* Include just some basic files to get the install going */
	include CLASS_PATH . '/ezDB.class.php';
	include CLASS_PATH . '/Template.class.php';
	include CLASS_PATH . '/TemplateSet.class.php';
	include CLASS_PATH . '/Vars.class.php';
}
else
{
	include CORE_PATH . '/codon.config.php';
}

include 'Installer.class.php';

Template::SetTemplatePath(SITE_ROOT.'/install/templates');
?>