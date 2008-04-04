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
			
				$this->CheckForUpdates();
				
				
				$this->ShowReportCounts();
				
				break;
				
			case 'about':
				
				Template::Show('core_about.tpl');
				
				break;
		}
	}
	
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
	
	
	function ShowReportCounts()
	{
		
		// Recent PIREP #'s
		
		/*
		for ($x = 1; $x < 32; $x++) {
			$lower_bound = mktime(0,0,0,date('m'),$x,date('Y'));
			$upper_bound = mktime(0,0,0,date('m'),$x + 1,date('Y'));
			$query = "SELECT count(id) FROM orders WHERE timeordered > FROM_UNIXTIME($lower_bound) AND timeordered < FROM_UNIXTIME($upper_bound)";
			$res = DB::get_var($query);
			$data[] = $res; // add to data
			
			$max = ($res > $max) ? $res : $max; // for y-axis label scaling
		}
		*/
		
		$reports = PIREPData::GetReportCount();
		
		$lineChart = new gLineChart;
				
		$values = array();
		$valueLabels = array();
		foreach($reports as $report)
		{
			array_push($values, $report->count);
		}
		
		$lineChart->width = 500;
		$lineChart->addDataSet($values);
		$lineChart->valueLabels = array('Report Submissions');
				
		echo '<img src="'. $lineChart->getUrl() . '" />';

	}
}
?>