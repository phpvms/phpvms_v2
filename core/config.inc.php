<?php
/**
 * LiveFrame - www.nsslive.net
 *	
 * Main Config file
 * 
 * revision updates:
 *	6 - Vars class added
 *	5 - DB class information added
 *		BaseTemplate path moved to index.php
 */
 
session_start();

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');

define('SITE_ROOT', str_replace('/core', '', dirname(__FILE__)));
define('CORE_PATH', dirname(__FILE__) );
define('CACHE_PATH', CORE_PATH . '/cache');
define('CLASS_PATH', CORE_PATH . '/classes');
define('MODULES_PATH', CORE_PATH . '/modules');
define('TEMPLATES_PATH', CORE_PATH . '/templates');

include CORE_PATH . '/site_config.inc.php';

//Module/Folder_Name => Name of Controller file

//$ACTIVE_MODULES['TestModule'] = MODULES_PATH . '/TestModule/TestModuleController.php';

$ACTIVE_MODULES['Login'] = MODULES_PATH . '/Login/Login.php';
$ACTIVE_MODULES['ACARS'] = MODULES_PATH . '/ACARS/ACARS.php';
$ACTIVE_MODULES['Contact'] = MODULES_PATH . '/Contact/Contact.php';
$ACTIVE_MODULES['PIREPS'] = MODULES_PATH . '/PIREPS/PIREPS.php';
$ACTIVE_MODULES['PilotProfile'] = MODULES_PATH . '/PilotProfile/PilotProfile.php';
$ACTIVE_MODULES['Registration'] = MODULES_PATH . '/Registration/Registration.php';

define('LIB_PATH', SITE_ROOT.'/lib');
define('SKINS_PATH', LIB_PATH.'/skins/' . CURRENT_SKIN);
define('CACHE_TIMEOUT', 24); //hours

// Include all dependencies
include CLASS_PATH . '/Auth.class.php';
include CLASS_PATH . '/DB.class.php';
include CLASS_PATH . '/MainController.class.php';
include CLASS_PATH . '/ModuleBase.class.php';
include CLASS_PATH . '/SessionManager.class.php';
include CLASS_PATH . '/Template.class.php';
include CLASS_PATH . '/TemplateSet.class.php';
include CLASS_PATH . '/Vars.class.php';
include CLASS_PATH . '/UserGroups.class.php';
include CLASS_PATH . '/Util.class.php';

if(DBASE_NAME != '')
{
	DB::init();
	DB::connect();
}

Template::SetTemplatePath(TEMPLATES_PATH);
Util::LoadSiteSettings();

$NAVBAR = '';
$HTMLHead = '';
?>