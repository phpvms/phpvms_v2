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

			// View bids for the pilot
			case 'bids':
				
				if(!Auth::LoggedIn()) return;
			
				$this->ShowBids();
		
				break;
				
			case 'addbid':
				
				if(!Auth::LoggedIn()) return;
				
				$routeid = $this->post->id;
				
				SchedulesData::AddBid(Auth::$userinfo->pilotid, $routeid);
				
				break;
				
			case 'removebid':
				
				if(!Auth::LoggedIn()) return;
				
				SchedulesData::RemoveBid($this->post->id);
				
				break;
		}
	}
	
	function ShowSchedules()
	{
		/*$depapts = SchedulesData::GetDepartureAirports();
		$appapts = SchedulesData::GetArrivalAiports();*/
		$depapts = OperationsData::GetAllAirports();
		$equip = OperationsData::GetAllAircraft();
		
		Template::Set('depairports', $depapts);
		Template::Set('equipment', $equip);
		
		Template::Show('schedule_searchform.tpl');
		
		// Show the routes. Remote this to not show them.
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