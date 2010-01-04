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
		$this->set('sidebar', 'sidebar_dashboard.tpl');
	}
	
	public function index()
	{
		/* Dashboard.tpl calls the functions below
		 */
		
		$this->CheckForUpdates();
		CentralData::send_vastats();
		
		$this->set('unexported_count', count(PIREPData::getReportsByExportStatus(false)));
		$this->render('dashboard.tpl');
		
		
		/*$this->set('allpilots', PilotData::GetPendingPilots());
		$this->render('pilots_pending.tpl');*/
	}
	
	public function pirepcounts()
	{
		# Create the chart
		//$reportcounts = '';
		$data = PIREPData::getIntervalDataByDays(array(), 7);
		
		if(!$data)
		{
			return false;
		}
		
		$bar_values = array();
		$bar_titles = array();
		foreach($data as $val)
		{
			$bar_titles[] = $val->ym;
			$bar_values[] = floatval($val->total);
		}
		
		include CORE_LIB_PATH.'/php-ofc-library/open-flash-chart.php';

		$title = new title( 'Past 30 days PIREPs' );

		// ------- LINE 2 -----
		$d = new solid_dot();
		$d->size(3)->halo_size(1)->colour('#3D5C56');

		$line = new line();
		$line->set_default_dot_style($d);
		$line->set_values( $bar_values );
		$line->set_width( 2 );
		//$line->set_colour( '#3D5C56' );
		
		$x_labels = new x_axis_labels();
		$x_labels->set_labels( $bar_titles );

		$x = new x_axis();
		$x->set_labels( $x_labels );
		
		$chart = new open_flash_chart();
		//$chart->set_title( $title );
		$chart->add_element( $line );
		$chart->set_y_axis( $y );
		$chart->set_x_axis( $x );
		$chart->set_bg_colour( '#FFFFFF' );

		echo $chart->toPrettyString();
	}
	
	public function about()
	{
		$this->render('core_about.tpl');
	}

	public function CheckInstallFolder()
	{
		if(file_exists(SITE_ROOT.'/install'))
		{
			$this->set('message', 'The install folder still exists!! This poses a security risk. Please delete it immediately');
			$this->render('core_error.tpl');
		}
	}

	/**
	 * Show the notification that an update is available
	 */
	public function CheckForUpdates()
	{
		if(NOTIFY_UPDATE == true)
		{
			$url = Config::Get('PHPVMS_API_SERVER').'/version/get/xml/'.PHPVMS_VERSION;
			
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
						
				$this->set('latestnews', $msg);
				return;
			}
			
			
			$xml = @simplexml_load_string($contents);
			
			if(!$xml)
			{
				$msg = '<br /><b>Error:</b> There was an error retrieving news. It may be temporary.
						Check to make sure allow_url_fopen is set to ON in your php.ini, or 
						that the cURL module is installed (contact your host).';
				
				$this->set('latestnews', $msg);
				return;
			}
			
			$postversion = intval(str_replace('.', '', trim($xml->version)));
			$currversion = intval(str_replace('.', '', PHPVMS_VERSION));
			
			if($currversion < $postversion)
			{
				$this->set('message', 'Version '.$version.' is available for download! Please update ASAP');
				$this->set('updateinfo', Template::GetTemplate('core_error.tpl', true));
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
			
			$this->set('phpvms_news', $contents);
			
			if(Config::Get('VACENTRAL_ENABLED') == true)
			{
				/* Get the latest vaCentral News */
				$contents = $file->get(Config::Get('VACENTRAL_NEWS_FEED'));
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
				
				$this->set('vacentral_news', $contents);
			}
		}
	}
}