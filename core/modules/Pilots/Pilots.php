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
 
class Pilots extends CodonModule
{
	
	public function index()
	{
		// Get all of our hubs, and list pilots by hub
		$allhubs = OperationsData::GetAllHubs();
		
		if(!$allhubs) $allhubs = array();
		
		foreach($allhubs as $hub)
		{
			$this->set('title', $hub->name);
			$this->set('icao', $hub->icao);
			
			$this->set('allpilots', PilotData::GetAllPilotsByHub($hub->icao));
								
			$this->render('pilots_list.tpl');
		}
		
		$nohub = PilotData::GetAllPilotsByHub('');
		if(!$nohub)
		{
			return;
		}
		
		$this->set('title', 'No Hub');
		$this->set('icao', '');
		$this->set('allpilots', $nohub);
		$this->render('pilots_list.tpl');
	}
	
	public function reports($pilotid='')
	{
		if($pilotid == '')
		{
			$this->set('message', 'No pilot specified!');
			$this->render('core_error.tpl');
			return;
		}
		
		$this->set('pireps', PIREPData::GetAllReportsForPilot($pilotid));
		$this->render('pireps_viewall.tpl');
	}
	
	public function RecentFrontPage($count = 5)
	{
		$this->set('pilots', PilotData::GetLatestPilots($count));
		$this->render('frontpage_recentpilots.tpl');
	}
}