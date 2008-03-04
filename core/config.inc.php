<?php
/**
 * LiveFrame - www.nsslive.net
 *	
 * Main Config file
 * 
 */ 
session_start();

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');

define('SITE_ROOT', str_replace('/core', '', dirname(__FILE__)));
define('CORE_PATH', dirname(__FILE__) );
define('CACHE_PATH', CORE_PATH . '/cache');
define('COMMON_PATH', CORE_PATH . '/common');
define('CLASS_PATH', CORE_PATH . '/classes');
define('DATE_FORMAT', 'm/d/Y');
define('MODULES_PATH', CORE_PATH . '/modules');
define('TEMPLATES_PATH', CORE_PATH . '/templates');
define('ADMIN_PATH', SITE_ROOT . '/admin');

// These are the core modules
//	Module/Folder_Name => Name of Controller file
$ACTIVE_MODULES['Login'] = MODULES_PATH . '/Login/Login.php';
$ACTIVE_MODULES['PilotProfile'] = MODULES_PATH . '/PilotProfile/PilotProfile.php';
$ACTIVE_MODULES['Registration'] = MODULES_PATH . '/Registration/Registration.php';
$ACTIVE_MODULES['Frontpage'] = MODULES_PATH . '/Frontpage/Frontpage.php';
$ACTIVE_MODULES['ACARS'] = MODULES_PATH . '/ACARS/ACARS.php';
$ACTIVE_MODULES['Contact'] = MODULES_PATH . '/Contact/Contact.php';
$ACTIVE_MODULES['PIREPS'] = MODULES_PATH . '/PIREPS/PIREPS.php';

// Determine our administration modules

$ADMIN_MODULES['NewsItems'] = ADMIN_PATH . '/modules/NewsItems/NewsItems.php';
$ADMIN_MODULES['PilotAdmin'] = ADMIN_PATH . '/modules/PilotAdmin/PilotAdmin.php';
$ADMIN_MODULES['Settings'] = ADMIN_PATH . '/modules/Settings/Settings.php';

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

// Common Classes
include COMMON_PATH . '/ACARSData.class.php';
include COMMON_PATH . '/NewsData.class.php';
include COMMON_PATH . '/PilotData.class.php';
include COMMON_PATH . '/PilotGroups.class.php';
include COMMON_PATH . '/PIREPData.class.php';
include COMMON_PATH . '/RegistrationData.class.php';
include COMMON_PATH . '/SettingsData.class.php';

include CORE_PATH . '/site_config.inc.php';

if(DBASE_NAME != '')
{
	DB::init();
	DB::connect();
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