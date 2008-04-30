<?php
/*
	Codon PHP Framework
	www.nsslive.net/codon

 Software License Agreement (BSD License)
 
 Copyright (c) 2008 Nabeel Shahzad, nsslive.net

 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions
 are met:

 1. Redistributions of source code must retain the above copyright
    notice, this list of conditions and the following disclaimer.
 2.  Redistributions in binary form must reproduce the above copyright
    notice, this list of conditions and the following disclaimer in the
    documentation and/or other materials provided with the distribution.
 3. The name of the author may not be used to endorse or promote products
    derived from this software without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

define('PIREP_PENDING', 0);
define('PIREP_ACCEPTED', 1);
define('PIREP_REJECTED', 2);
define('PIREP_INPROGRESS', 3); // value of 3

define('PILOT_PENDING', 0);
define('PILOT_ACCEPTED', 1);
define('PILOT_REJECTED', 2);

$Config['PAGE_EXT'] = '.htm';

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