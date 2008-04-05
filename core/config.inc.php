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

$Config['PHPVMS_VERSION'] = '0.9.273';

define('SITE_ROOT', str_replace('/core', '', dirname(__FILE__)));
define('CORE_PATH', dirname(__FILE__) );
define('CLASS_PATH', CORE_PATH . '/classes');
define('MODULES_PATH', CORE_PATH . '/modules');
define('TEMPLATES_PATH', CORE_PATH . '/templates');
define('CACHE_PATH', CORE_PATH . '/cache');
define('COMMON_PATH', CORE_PATH . '/common');
define('PAGES_PATH', CORE_PATH . '/pages');
define('ADMIN_PATH', SITE_ROOT . '/admin');

if(!file_exists(CORE_PATH.'/site_config.inc.php') 
	|| filesize(CORE_PATH.'/site_config.inc.php') == 0)
{
	header('Location: install/install.php');	
}

// These are the core modules
//	Module/Folder_Name => Name of Controller file
$ACTIVE_MODULES['Login'] = MODULES_PATH . '/Login/Login.php';
$ACTIVE_MODULES['PilotProfile'] = MODULES_PATH . '/PilotProfile/PilotProfile.php';
$ACTIVE_MODULES['Registration'] = MODULES_PATH . '/Registration/Registration.php';
$ACTIVE_MODULES['Frontpage'] = MODULES_PATH . '/Frontpage/Frontpage.php';
$ACTIVE_MODULES['Schedules'] = MODULES_PATH . '/Schedules/Schedules.php';
$ACTIVE_MODULES['Pages'] = MODULES_PATH . '/Pages/Pages.php';
$ACTIVE_MODULES['PIREPS'] = MODULES_PATH . '/PIREPS/PIREPS.php';
$ACTIVE_MODULES['Contact'] = MODULES_PATH . '/Contact/Contact.php';

// Determine our administration modules
$ADMIN_MODULES['Dashboard'] = ADMIN_PATH . '/modules/Dashboard/DashboardAdmin.php';
$ADMIN_MODULES['SiteCMS'] = ADMIN_PATH . '/modules/SiteCMS/SiteCMS.php';
$ADMIN_MODULES['PilotAdmin'] = ADMIN_PATH . '/modules/PilotAdmin/PilotAdmin.php';
$ADMIN_MODULES['PIREPAdmin'] = ADMIN_PATH . '/modules/PIREPAdmin/PIREPAdmin.php';
$ADMIN_MODULES['OperationsAdmin'] = ADMIN_PATH . '/modules/OperationsAdmin/OperationsAdmin.php';
$ADMIN_MODULES['SettingsAdmin'] = ADMIN_PATH . '/modules/Settings/Settings.php';

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
include COMMON_PATH . '/SiteData.class.php';
include COMMON_PATH . '/PilotData.class.php';
include COMMON_PATH . '/PilotGroups.class.php';
include COMMON_PATH . '/PIREPData.class.php';
include COMMON_PATH . '/RegistrationData.class.php';
include COMMON_PATH . '/OperationsData.class.php';
include COMMON_PATH . '/SchedulesData.class.php';
include COMMON_PATH . '/SettingsData.class.php';

include COMMON_PATH . '/GoogleChart.class.php';

include CORE_PATH . '/site_config.inc.php';

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

define('PIREP_PENDING', 0);
define('PIREP_ACCEPTED', 1);
define('PIREP_REJECTED', 2);
define('PIREP_INPROGRESS', 3); // value of 3


$NAVBAR = '';
$HTMLHead = '';
?>