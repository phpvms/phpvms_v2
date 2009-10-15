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
			Template::Set('title', $hub->name);
			Template::Set('icao', $hub->icao);
			
			Template::Set('allpilots', PilotData::GetAllPilotsByHub($hub->icao));
								
			Template::Show('pilots_list.tpl');
		}
		
		$nohub = PilotData::GetAllPilotsByHub('');
		if(!$nohub)
		{
			return;
		}
		
		Template::Set('title', 'No Hub');
		Template::Set('icao', '');
		Template::Set('allpilots', $nohub);
		Template::Show('pilots_list.tpl');
	}
	
	public function reports($pilotid='')
	{
		if($pilotid == '')
		{
			Template::Set('message', 'No pilot specified!');
			Template::Show('core_error.tpl');
			return;
		}
		
		Template::Set('pireps', PIREPData::GetAllReportsForPilot($pilotid));
		Template::Show('pireps_viewall.tpl');
	}
	
	public function RecentFrontPage($count = 5)
	{
		Template::Set('pilots', PilotData::GetLatestPilots($count));
		Template::Show('frontpage_recentpilots.tpl');
	}
}