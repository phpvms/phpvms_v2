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
	public function HTMLHead()
	{
		Template::Set('sidebar', 'sidebar_dashboard.tpl');
	}
	
	public function index()
	{
		/* Dashboard.tpl calls the functions below
				*/
		
		$this->CheckForUpdates();
		CentralData::send_vastats();
		
		Template::Set('unexported_count', count(PIREPData::getReportsByExportStatus(false)));
		Template::Show('dashboard.tpl');
		
		
		/*Template::Set('allpilots', PilotData::GetPendingPilots());
		Template::Show('pilots_pending.tpl');*/
	}
	
	public function pirepcounts()
	{
		# Create the chart
		//$reportcounts = '';
		$reportcounts = PIREPData::ShowReportCounts();
		if(!$reportcounts)
		{
			$reportcounts = array();
		}
		
		$graph = new ChartGraph('pchart', 'line', 680, 180);
		$graph->setFontSize(8);
		$graph->AddData($reportcounts, array_keys($reportcounts));
		$graph->setTitles('PIREPS Filed');
		$graph->GenerateGraph();
	}
	
	public function about()
	{
		Template::Show('core_about.tpl');

	}
	

	public function CheckInstallFolder()
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
	public function CheckForUpdates()
	{
		if(NOTIFY_UPDATE == true)
		{
			$url = Config::Get('PHPVMS_API_SERVER').'/version';
			
			# Default to fopen(), if that fails it'll use CURL
			$file = new CodonWebService();
			//$file->setType('fopen'); 
			$contents = @$file->get($url);
			
			# Something should have been returned
			if($contents == '')
			{
				$msg = '<br /><b>Error:</b> The phpVMS update server could not be contacted. 
						Check to make sure allow_url_fopen is set to ON in your php.ini, or 
						that the cURL module is installed (contact your host).';
						
				Template::Set('latestnews', $msg);
				return;
			}
			
			$contents = str_replace('\n', '', $contents);
			
			preg_match('/^.*Version: (.*)<\/span>/', $contents, $version_info);
			$version = $version_info[1];
			
			$postversion = intval(str_replace('.', '', trim($version)));
			$currversion = intval(str_replace('.', '', PHPVMS_VERSION));
			
			if($currversion < $postversion)
			{
				Template::Set('message', 'Version '.$version.' is available for download! Please update ASAP');
				Template::Set('updateinfo', Template::GetTemplate('core_error.tpl', true));
			}
			
			/* Retrieve latest news from Feedburner RSS, in case the phpVMS site is down
			 */
			$contents = $file->get(Config::Get('PHPVMS_NEWS_FEED'));
			$feed = simplexml_load_string($contents);
			$contents = '';
			
			$i=1;
			$count = 5; // Show the last 5
			foreach($feed->channel->item as $news)
			{
				$news_content = (string) $news->description;
				$date_posted = str_replace('-0400', '', (string) $news->pubDate);
				
				$contents.="<div class=\"newsitem\">
								<b>{$news->title}</b> {$news_content}
								<br /><br />
								Posted: {$date_posted}
							</div>";
				
				if($i++ == $count)
					break;
			}
			
			Template::Set('latestnews', $contents);
		}
	}
}