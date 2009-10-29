<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
 
/**
 * phpVMS Installer File
 *  "Boot" file includes our basic "needs" for the installer
 */

define('SITE_ROOT', str_replace('install', '', dirname(__FILE__)));
define('CORE_PATH', SITE_ROOT . 'core/');
define('CLASS_PATH', CORE_PATH . 'classes/');


if(!file_exists(CORE_PATH.'/local.config.php') || filesize(CORE_PATH.'/local.config.php') == 0)
{
	define('DS', DIRECTORY_SEPARATOR);
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