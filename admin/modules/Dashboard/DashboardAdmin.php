<?php

/**
 * This file handles any misc tasks that need to be done.
 * Loaded at the very end
 */

class Dashboard
{

	function Controller()
	{
		/*
		 * Check for updates
		 */
		switch($_GET['admin'])
		{
			case '':

				/* Dashboard.tpl calls the functions below
				*/
				Template::Show('dashboard.tpl');
				break;

			case 'about':

				Template::Show('core_about.tpl');

				break;
		}
	}

	/**
	 * Show the notification that an update is available
	 */
	function CheckForUpdates()
	{
		if(NOTIFY_UPDATE == true)
		{
			$postversion = @file_get_contents('http://www.phpvms.net/version.php');

			if(trim($postversion) != PHPVMS_VERSION && $postversion !== false)
			{
				Template::Set('message', 'An update for phpVMS is available!');
				Template::Show('core_error.tpl');
			}
		}
	}
}
?>