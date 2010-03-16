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

function pre_module_load()
{	
	if(is_dir(CORE_PATH.'/local.config.php'))
	{
		Debug::showCritical('core/local.config.php is a folder, not a file. Please delete and create as a file');
		die();
	}
	
	if(!file_exists(CORE_PATH.'/local.config.php') || filesize(CORE_PATH.'/local.config.php') == 0)
	{
		Debug::showCritical('phpVMS has not been installed yet! Goto <a href="install/install.php">install/install.php</a> to start!');		
		exit;
	}
	
	SiteData::loadSiteSettings();
	Auth::StartAuth();
}

function post_module_load()
{
	/* Misc tasks which need to get done */
	
	/* If the setting to auto-retired pilots is on, then do that
		and only check every 24 hours
	 */
	 if(Config::Get('USE_CRON') === true)
	 {
		if(Config::Get('PILOT_AUTO_RETIRE') == true)
		{
			$within_timelimit = CronData::check_hoursdiff('find_retired_pilots', '24');
			if($within_timelimit == false)
			{
				PilotData::findRetiredPilots();
				CronData::set_lastupdate('find_retired_pilots');
			}
		}
		
		/* Expenses, make sure they're all populated */
		$within_timelimit = CronData::check_hoursdiff('populate_expenses', '18');
		if($within_timelimit == false)
		{
			FinanceData::updateAllExpenses();
			CronData::set_lastupdate('populate_expenses');
		}
	}

	// @TODO: Clean ACARS records older than one month
		
   return true;
}

function url($path)
{
	$url = SITE_URL;
		
	if($path[0] != '/')
		$path='/'.$path;
			
	if(Config::Get('URL_REWRITE') == true)
	{
		return $url.$path;
	}
		
	return $url.'/index.php'.$path;
}

function actionurl($path)
{
	$url = SITE_URL;
	
	if($path[0] != '/')
		$path='/'.$path;
	
	if(Config::Get('URL_REWRITE') == true)
	{
		return $url.$path;
	}
	
	return $url.'/action.php'.$path;
}

function fileurl($path)
{
	$url = SITE_URL;
		
	if($path[0]!='/')
		$path='/'.$path;
	
	return $url.$path;
}


function html_url($title, $url)
{
	return '<a href="'.url($url).'" >'.$title.'</a>';
}