<?php

define('PIREP_PENDING', 0);
define('PIREP_ACCEPTED', 1);
define('PIREP_REJECTED', 2);
define('PIREP_INPROGRESS', 3); // value of 3

define('PILOT_PENDING', 0);
define('PILOT_ACCEPTED', 1);
define('PILOT_REJECTED', 2);

$Config['PAGE_EXT'] = '.htm';
$Config['PILOTID_OFFSET'] = 1000; // Start the Pilot's ID from 1000

// Common Classes
include COMMON_PATH . '/Auth.class.php';
include COMMON_PATH . '/ACARSData.class.php';
include COMMON_PATH . '/SiteData.class.php';
include COMMON_PATH . '/PilotData.class.php';
include COMMON_PATH . '/PilotGroups.class.php';
include COMMON_PATH . '/PIREPData.class.php';
include COMMON_PATH . '/RanksData.class.php';
include COMMON_PATH . '/RegistrationData.class.php';
include COMMON_PATH . '/RSSFeed.class.php';
include COMMON_PATH . '/OperationsData.class.php';
include COMMON_PATH . '/SchedulesData.class.php';
include COMMON_PATH . '/SettingsData.class.php';
include COMMON_PATH . '/StatsData.class.php';
include COMMON_PATH . '/UserGroups.class.php';

include COMMON_PATH . '/GoogleChart.class.php';
include COMMON_PATH . '/GoogleMap.class.php';

// These are the core modules
//	Module/Folder_Name => Name of Controller file
$ACTIVE_MODULES['Login'] = MODULES_PATH . '/Login/Login.php';
$ACTIVE_MODULES['PilotProfile'] = MODULES_PATH . '/PilotProfile/PilotProfile.php';
$ACTIVE_MODULES['Registration'] = MODULES_PATH . '/Registration/Registration.php';
$ACTIVE_MODULES['Frontpage'] = MODULES_PATH . '/Frontpage/Frontpage.php';
$ACTIVE_MODULES['Schedules'] = MODULES_PATH . '/Schedules/Schedules.php';
$ACTIVE_MODULES['Pages'] = MODULES_PATH . '/Pages/Pages.php';
$ACTIVE_MODULES['Pilots'] = MODULES_PATH . '/Pilots/Pilots.php';
$ACTIVE_MODULES['PIREPS'] = MODULES_PATH . '/PIREPS/PIREPS.php';
$ACTIVE_MODULES['Contact'] = MODULES_PATH . '/Contact/Contact.php';

// Determine our administration modules
$ADMIN_MODULES['Dashboard'] = ADMIN_PATH . '/modules/Dashboard/DashboardAdmin.php';
$ADMIN_MODULES['SiteCMS'] = ADMIN_PATH . '/modules/SiteCMS/SiteCMS.php';
$ADMIN_MODULES['PilotAdmin'] = ADMIN_PATH . '/modules/PilotAdmin/PilotAdmin.php';
$ADMIN_MODULES['PIREPAdmin'] = ADMIN_PATH . '/modules/PIREPAdmin/PIREPAdmin.php';
$ADMIN_MODULES['PilotRanking'] = ADMIN_PATH . '/modules/PilotRanking/RankingAdmin.php';
$ADMIN_MODULES['OperationsAdmin'] = ADMIN_PATH . '/modules/OperationsAdmin/OperationsAdmin.php';
$ADMIN_MODULES['SettingsAdmin'] = ADMIN_PATH . '/modules/Settings/Settings.php';

?>