<?php
/**
 * Codon Framework
 *  http://www.nsslive.net/codon
 *
 * Main Config file
 *
 */
session_start();

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');

$Config['PHPVMS_VERSION'] = '0.9.286';

define('SITE_ROOT', str_replace('/core', '', dirname(__FILE__)));
define('CORE_PATH', dirname(__FILE__) );
define('CLASS_PATH', CORE_PATH . '/classes');
define('MODULES_PATH', CORE_PATH . '/modules');
define('TEMPLATES_PATH', CORE_PATH . '/templates');
define('CACHE_PATH', CORE_PATH . '/cache');
define('COMMON_PATH', CORE_PATH . '/common');
define('PAGES_PATH', CORE_PATH . '/pages');
define('ADMIN_PATH', SITE_ROOT . '/admin');

if(!file_exists(CORE_PATH.'/local.config.php')
	|| filesize(CORE_PATH.'/local.config.php') == 0)
{
	header('Location: install/install.php');
}

// Include all dependencies
include CLASS_PATH . '/DB.class.php';
include CLASS_PATH . '/EventDispatch.class.php';
include CLASS_PATH . '/JSON.class.php';
include CLASS_PATH . '/MainController.class.php';
include CLASS_PATH . '/ModuleBase.class.php';
include CLASS_PATH . '/SessionManager.class.php';
include CLASS_PATH . '/Template.class.php';
include CLASS_PATH . '/TemplateSet.class.php';
include CLASS_PATH . '/Vars.class.php';
include CLASS_PATH . '/Util.class.php';

include CORE_PATH . '/app.config.php';
include CORE_PATH . '/local.config.php';

if(DBASE_NAME != '')
{
	DB::init(DBASE_TYPE);
	DB::connect(DBASE_USER, DBASE_PASS, DBASE_NAME, DBASE_SERVER);
	DB::hide_errors();
	DB::query('SET FOREIGN_KEY_CHECKS=1;'); // Compensate for host-side setting
}

Auth::StartAuth();
Template::SetTemplatePath(TEMPLATES_PATH);
Util::LoadSiteSettings();

define('LIB_PATH', SITE_ROOT.'/lib');
define('SKINS_PATH', LIB_PATH.'/skins/' . CURRENT_SKIN);
define('CACHE_TIMEOUT', 24); //hours


$NAVBAR = '';
$HTMLHead = '';
?>