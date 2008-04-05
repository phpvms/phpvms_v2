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
	
	/**
	 * Show the graph of the past week's reports
	 */
	function ShowReportCounts()
	{
		// Recent PIREP #'s
		$max = 0;
		$data = array();
		
		// This is for the past 7 days
		for($i=-7;$i<=0;$i++)
		{
			$date = mktime(0,0,0,date('m'), date('d') + $i ,date('Y'));		
			$count = PIREPData::GetReportCount($date);
			
			array_push($data, intval($count));
			$label .= date('m/d', $date) .'|';
			
			if($count > $max) 
				$max = $count;
		}
						
		$chart = new googleChart($data);
		$chart->dimensions = '700x200';
		$chart->setLabelsMinMax($max,'left');
		$chart->setLabels($label,'bottom');
		
		echo '<img src="'.$chart->draw(false).'" align="center" />';
	}
}
?>