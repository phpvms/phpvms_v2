<?php

define('PIREP_PENDING', 0);
define('PIREP_ACCEPTED', 1);
define('PIREP_REJECTED', 2);
define('PIREP_INPROGRESS', 3); // value of 3

define('PILOT_PENDING', 0);
define('PILOT_ACCEPTED', 1);
define('PILOT_REJECTED', 2);

$Config['PAGE_EXT'] = '.htm';
$Config['PILOTID_OFFSET'] = 0; // Start the Pilot's ID from 1000

$Config['MAP_WIDTH'] = '600px';
$Config['MAP_HEIGHT'] = '400px';

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
?>