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
		if(isset($this->post->action) && $this->post->action == 'findflight')
		{
			$this->FindFlight();
			return;
		}
		
		$this->showSchedules();
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
			$this->set('message', 'You must be logged in to access this feature!');
			$this->render('core_error.tpl');
			return;
		}
				
		if(!is_numeric($routeid))
		{
			preg_match('/^([A-Za-z]{3})(\d*)/', $routeid, $matches);
			$routeid = $matches[2];
		}
		
		$scheddata = SchedulesData::GetScheduleDetailed($routeid);
		$counts = SchedulesData::GetScheduleFlownCounts($scheddata->code, $scheddata->flightnum);
									
		$this->set('schedule', $scheddata);
		$this->set('scheddata', $counts); // past 30 days
		
		$this->render('schedule_details.tpl');
		$this->render('route_map.tpl');
	}
	
	public function brief($routeid = '')
	{	
		if($routeid == '')
		{
			$this->set('message', 'You must be logged in to access this feature!');
			$this->render('core_error.tpl');
			return;
		}
		
		$scheddata = SchedulesData::GetScheduleDetailed($routeid);
		
		$this->set('schedule', $scheddata);
		$this->render('schedule_briefing.tpl');
	}
	
	public function boardingpass($routeid)
	{
		if($routeid == '')
		{
			$this->set('message', 'You must be logged in to access this feature!');
			$this->render('core_error.tpl');
			return;
		}
		
		$scheddata = SchedulesData::GetScheduleDetailed($routeid);
				
		$this->set('schedule', $scheddata);
		$this->render('schedule_boarding_pass.tpl');
	}
	
	public function bids()
	{
		if(!Auth::LoggedIn()) return;
			
		$this->set('bids', SchedulesData::GetBids(Auth::$userinfo->pilotid));
		$this->render('schedule_bids.tpl');
	}
	
	public function addbid()
	{
		if(!Auth::LoggedIn()) return;
				
		$routeid = $this->post->id;
		
		if($routeid == '')
		{
			$this->set('message', 'No route!');
			$this->render('core_error.tpl');
			return;
		}
		
		// See if this is a valid route
		$route = SchedulesData::GetSchedule($routeid);
		if(!$route)
		{
			$this->set('message', 'Invalid route!');
			$this->render('core_error.tpl');
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
		
		$ret = SchedulesData::AddBid(Auth::$userinfo->pilotid, $routeid);
		CodonEvent::Dispatch('bid_added', 'Schedules', $routeid);
		
		if($ret === true)
		{
			$this->set('message', 'Bid Added!');
			$this->render('core_success.tpl');
		}
		else
		{
			$this->set('message', 'You must be logged in to access this feature!');
			$this->render('core_error.tpl');
		}
	}
	
	public function removebid()
	{
		if(!Auth::LoggedIn()) return;
				
		SchedulesData::RemoveBid($this->post->id);
	}

	public function showSchedules()
	{
		$depapts = OperationsData::GetAllAirports();
		$equip = OperationsData::GetAllAircraftSearchList(true);
		
		$this->set('depairports', $depapts);
		$this->set('equipment', $equip);
		
		$this->render('schedule_searchform.tpl');
		
		# Show the routes. Remote this to not show them.
		$this->set('allroutes', SchedulesData::GetSchedules());
		
		$this->render('schedule_list.tpl');
	}
	
	public function findFlight()
	{
		
		if($this->post->depicao != '')
		{
			$params = array('s.depicao' => $this->post->depicao);
		}
		
		if($this->post->arricao != '')
		{
			$params = array('s.arricao' => $this->post->arricao);
		}
		
		if($this->post->equipment != '')
		{
			$params = array('a.name' => $this->post->equipment);
		}
		
		if($this->post->distance != '')
		{
			if($this->post->type == 'greater')
				$value = '> ';
			else
				$value = '< ';
			
			$value .= $this->post->distance;
			
			$params = array('s.distance' => $value);
		}
		
		$this->set('allroutes', SchedulesData::findSchedules($params));
		$this->render('schedule_results.tpl');
	}
}