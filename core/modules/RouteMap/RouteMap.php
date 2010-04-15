<?php

class RouteMap extends CodonModule 
{
	
	public function index()
	{
		
		if($this->get->maptype == 'hubmap')
		{
			// Show hubmap	
			$params = array(
				's.depicao'=>$this->get->hub, 
				's.enabled'=>1
			);
			
			// Show only 20 routes
			$allschedules = SchedulesData::findSchedules($params, Config::Get('ROUTE_MAP_SHOW_NUMBER'));
			
			if(count($allschedules) == 0)
			{
				echo 'There are no departures from this airport!';
				return;
			}
			
			$airportinfo = OperationsData::GetAirportInfo($this->get->hub);
			
			echo '<h3>Departures from '.$airportinfo->name.'</h3>';
			
		}
		else
		{
			# Get all of the schedule
			$allschedules = SchedulesData::findSchedules(array('s.enabled'=>1), Config::Get('ROUTE_MAP_SHOW_NUMBER'));
		}
		
		$this->set('allschedules', $allschedules);
		$this->render('flown_routes_map.tpl');
	}
}