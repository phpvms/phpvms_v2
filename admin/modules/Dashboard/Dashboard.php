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
 * @package module_admin_dashboard
 */
 
/**
 * This file handles any misc tasks that need to be done.
 * Loaded at the very end
 */

class Dashboard extends CodonModule
{
	function HTMLHead()
	{
		Template::Set('sidebar', 'sidebar_dashboard.tpl');
	}
	
	function Controller()
	{
		/*
		 * Check for updates
		 */
		switch($this->get->page)
		{
			default:

				/* Dashboard.tpl calls the functions below
				*/
				
				$this->CheckForUpdates();
				
				Template::Set('reportcounts', PIREPData::ShowReportCounts());
				Template::Show('dashboard.tpl');

                /*Template::Set('allpilots', PilotData::GetPendingPilots());
				Template::Show('pilots_pending.tpl');*/
				break;

			case 'about':

				Template::Show('core_about.tpl');

				break;
		}
	}

	function CheckInstallFolder()
	{
		if(file_exists(SITE_ROOT.'/install'))
		{
			Template::Set('message', 'The install folder still exists!! This poses a security risk. Please delete it immediately');
			Template::Show('core_error.tpl');
		}
	}

	/**
	 * Show the notification that an update is available
	 */
	function CheckForUpdates()
	{
		if(NOTIFY_UPDATE == true)
		{
			
			$url = 'http://www.phpvms.net/extern/version.php?name='.urlencode(SITE_NAME).'&url='.urlencode(SITE_URL)
					.'&version='.urlencode(PHPVMS_VERSION);
			
			$contents = @file_get_contents($url);
			
			preg_match('/^.*Version: (.*)<\/span>/', $contents, $version_info);
			$version = $version_info[1];
			
			$postversion = intval(str_replace('.', '', trim($version)));
			$currversion = intval(str_replace('.', '', PHPVMS_VERSION));
			
			if($currversion < $postversion)
			{
				Template::Set('message', 'Version '.$version.' is available for download! Please update ASAP');
				Template::Set('updateinfo', Template::GetTemplate('core_error.tpl', true));
			}
			
			# Always show the latest news	
			Template::Set('latestnews', $contents);
		}
	}
}
?>