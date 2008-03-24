<?php
/**
 * phpVMS Installer File
 *  "Boot" file includes our basic "needs" for the installer
 */

define('SITE_ROOT', str_replace('/install', '', dirname(__FILE__)));
define('CORE_PATH', dirname(__FILE__) );
define('CLASS_PATH', CORE_PATH . '/classes');

include CLASS_PATH . '/DB.class.php';
include CLASS_PATH . '/Template.class.php';
include CLASS_PATH . '/TemplateSet.class.php';
include CLASS_PATH . '/Vars.class.php';

Template::SetTemplatePath(SITE_ROOT.'/templates');
?>