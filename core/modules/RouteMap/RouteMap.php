<?php

class RouteMap extends CodonModule 
{
	
	public function __construct()
	{
		CodonRewrite::AddRule('routemap', array('maptype', 'hub'));		
	}
	
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
			$allschedules = SchedulesData::findSchedules($params, 20);
			
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
			$allschedules = SchedulesData::findSchedules(array('s.enabled'=>1), 20);
		}
		
		$this->ShowMap($allschedules);
	}
	
	public function ShowMap($allschedules)
	{
		$map = new GoogleMapAPI('routemap', 'phpVMS');
		$map->sidebar=false;
		
		$shownroutes = array();
		
		$centerlat = 0;
		$centerlong = 0;
		
		# Create map		
		foreach($allschedules as $schedule)
		{
			
			$route = $schedule->depicao.$schedule->arricao;
			
			if(in_array($route, $shownroutes))
			{
				continue;
			}
			else
			{
				$shownroutes[] = $route;
			}
			
			$map->addMarkerByCoords($schedule->deplong, $schedule->deplat, '', "$schedule->depname ($schedule->depicao)");
			$map->addMarkerByCoords($schedule->arrlong, $schedule->arrlat, '', "$schedule->arrname ($schedule->arricao)");
			
			$map->addPolyLineByCoords($schedule->deplong, $schedule->deplat, $schedule->arrlong, $schedule->arrlat, 
					Config::Get('MAP_LINE_COLOR'), 5, 50);
			
			$centerlat = ($schedule->deplat + $schedule->arrlat) / 2;
			$centerlong = ($schedule->deplong + $schedule->arrlong) / 2;
		}
		
		$centerlat = $centerlat / count($allschedules);
		$centerlong = $centerlong / count($allschedules);
		
		# Show the map				
		$map->adjustCenterCoords($centerlong, $centerlat);
		$map->setHeight(Config::Get('MAP_HEIGHT'));
		$map->setWidth(Config::Get('MAP_WIDTH'));
		$map->setMapType(Config::Get('MAP_TYPE'));
		
		$map->setAPIKey(GOOGLE_KEY);
		$map->printHeaderJS();
		$map->printMapJS();
		
		$map->printMap();
		$map->printOnLoad();
	}
}