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
		parent::__construct();
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
	
	public function index()
	{
		$this->view();
	}
	
	public function view()
	{
		if($this->post->action == 'findflight')
		{
			$this->FindFlight();
			return;
		}
		
		$this->ShowSchedules();
	}
	
	public function detail($routeid='')
	{
		$this->details($routeid);
	}
	
	public function details($routeid = '')
	{
		//$routeid = $this->get->id;
		
		if($routeid == '')
		{
			Template::Set('message', 'You must be logged in to access this feature!');
			Template::Show('core_error.tpl');
			return;
		}
				
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
	}
	
	public function brief($routeid = '')
	{	
		if($routeid == '')
		{
			Template::Set('message', 'You must be logged in to access this feature!');
			Template::Show('core_error.tpl');
			return;
		}
		
		$scheddata = SchedulesData::GetScheduleDetailed($routeid);
		
		Template::Set('schedule', $scheddata);
		Template::Show('schedule_briefing.tpl');
	}
	
	public function boardingpass($routeid)
	{
		if($routeid == '')
		{
			Template::Set('message', 'You must be logged in to access this feature!');
			Template::Show('core_error.tpl');
			return;
		}
		
		$scheddata = SchedulesData::GetScheduleDetailed($routeid);
				
		Template::Set('schedule', $scheddata);
		Template::Show('schedule_boarding_pass.tpl');
	}
	
	public function bids()
	{
		if(!Auth::LoggedIn()) return;
			
		Template::Set('bids', SchedulesData::GetBids(Auth::$userinfo->pilotid));
		Template::Show('schedule_bids.tpl');
	}
	
	public function addbid()
	{
		if(!Auth::LoggedIn()) return;
				
		$routeid = $this->post->id;
		
		if($routeid == '')
		{
			return;
		}
		
		
		// See if this is a valid route
		$routeid = SchedulesData::GetSchedule($routeid);
		if(!$routeid)
		{
			return;
		}
		
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
	}
	
	public function removebid()
	{
		if(!Auth::LoggedIn()) return;
				
		SchedulesData::RemoveBid($this->post->id);
	}

	public function ShowSchedules()
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
	
	public function FindFlight()
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
		
		Template::Show('schedule_results.tpl');
	}
	

}