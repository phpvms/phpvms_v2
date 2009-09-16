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
 
class Schedules extends CodonModule
{
	
	public $gMap;
	
	public function __construct()
	{
		$this->gMap = new GoogleMapAPI('routemap', 'phpVMS');
		$this->gMap->setAPIKey(GOOGLE_KEY);
	}
	
	public function HTMLHead()
	{
		if($this->get->page == 'detail' || $this->get->page == 'details')
		{
			$this->gMap->printHeaderJS();
    		$this->gMap->printMapJS();			
		}
	}
	
	function Controller()
	{
		switch($this->get->page)
		{
			case '':
			case 'view':
								
				if($this->post->action == 'findflight')
				{
					$this->FindFlight();
					return;
				}
				
				$this->ShowSchedules();
				
				break;
				
			case 'detail':
			case 'details':
				
				$routeid = $this->get->id;
				
				if(!is_numeric($routeid))
				{
					preg_match('/^([A-Za-z]{3})(\d*)/', $routeid, $matches);
					$routeid = $matches[2];
				}
				
				$scheddata = SchedulesData::GetScheduleDetailed($routeid);
				$counts = SchedulesData::GetScheduleFlownCounts($scheddata->code, $scheddata->flightnum);
											
				Template::Set('schedule', $scheddata);
				Template::Set('scheddata', $counts); // past 30 days
				
				Template::Show('schedule_details.tpl');
				Template::Show('route_map.tpl');
				
			
				break;
				
			case 'brief':
				
				$routeid = $this->get->id;
				
				$scheddata = SchedulesData::GetScheduleDetailed($routeid);
				
				Template::Set('schedule', $scheddata);
				Template::Show('schedule_briefing.tpl');
				
				break;
				
			case 'boardingpass':
			
				$routeid = $this->get->id;
				$scheddata = SchedulesData::GetScheduleDetailed($routeid);
				
				Template::Set('schedule', $scheddata);
				Template::Show('schedule_boarding_pass.tpl');
				
				break;
				

			// View bids for the pilot
			case 'bids':
				
				if(!Auth::LoggedIn()) return;
			
				$this->ShowBids();
		
				break;
				
			case 'addbid':
				
				if(!Auth::LoggedIn()) return;
				
				$routeid = $this->post->id;
				
				if(CodonEvent::Dispatch('bid_preadd', 'Schedules', $routeid)==false)
				{
					return;
				}
				
				/* Block any other bids if they've already made a bid
				 */
				if(Config::Get('DISABLE_BIDS_ON_BID') == true)
				{
					$bids = SchedulesData::GetBids(Auth::$userinfo->pilotid);
					
					# They've got somethin goin on
					if(count($bids) > 0)
					{
						return;
					}					
				}
				
				SchedulesData::AddBid(Auth::$userinfo->pilotid, $routeid);
				
				CodonEvent::Dispatch('bid_added', 'Schedules', $routeid);
				
				break;
				
			case 'removebid':
				
				if(!Auth::LoggedIn()) return;
				
				SchedulesData::RemoveBid($this->post->id);
				
				break;
		}
	}
	
	function ShowSchedules()
	{
		$depapts = OperationsData::GetAllAirports();
		$equip = OperationsData::GetAllAircraftSearchList(true);
		
		Template::Set('depairports', $depapts);
		Template::Set('equipment', $equip);
		
		Template::Show('schedule_searchform.tpl');
		
		# Show the routes. Remote this to not show them.
		Template::Set('allroutes', SchedulesData::GetSchedules());
		
		Template::Show('schedule_list.tpl');
	}
	
	function FindFlight()
	{
		
		if($this->post->depicao != '')
		{
			Template::Set('allroutes', SchedulesData::GetRoutesWithDeparture($this->post->depicao));
		}
		
		if($this->post->arricao != '')
		{
			Template::Set('allroutes', SchedulesData::GetRoutesWithArrival($this->post->arricao));
		}
		
		if($this->post->equipment != '')
		{
			Template::Set('allroutes', SchedulesData::GetSchedulesByEquip($this->post->equipment));
		}
		
		if($this->post->distance != '')
		{
			if($this->post->type == 'greater')
				$type = '>';
			else
				$type = '<';
				
			Template::Set('allroutes', SchedulesData::GetSchedulesByDistance($this->post->distance, $type));
		}
		
		/*DB::debug();*/
		
		Template::Show('schedule_results.tpl');
	}
	
	
	function ShowBids()
	{
		Template::Set('bids', SchedulesData::GetBids(Auth::$userinfo->pilotid));
		Template::Show('schedule_bids.tpl');
	}
}

?>