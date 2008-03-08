<?php


class OperationsAdmin
{
	function NavBar()
	{
		echo '<li><a href="#">Operations</a>
				<ul>
					<li><a href="?admin=aircraft">Aircraft</a></li>
					<li><a href="?admin=airports">Airports</a></li>
					<li><a href="?admin=schedules">Flight Schedules</a></li>
				</ul>
				</li>';
	}
	
	function Controller()
	{
		switch(Vars::GET('admin'))
		{
			case 'aircraft':
			
				/* If they're adding an aircraft, go through this pain
				*/				 
				if(Vars::POST('action') == 'addaircraft')
				{
					$this->AddAircraft();
				}
			
				Template::Set('allaircraft', OperationsData::GetAllAircraft());
				Template::Show('ops_aircraftlist.tpl');
				
				Template::Show('ops_addaircraft.tpl');
				
				break;
				
			case 'airports':
			
				/* If they're adding an airport, go through this pain
				*/				 
				if(Vars::POST('action') == 'addairport')
				{
					$this->AddAirport();
				}
				 
				Template::Set('airports', OperationsData::GetAllAirports());
				Template::Show('ops_airportlist.tpl');
				
				Template::Show('ops_addairport.tpl');
				break;
			case 'schedules':
			
				$this->ViewSchedules();
				break;
		}
	}
	
	function AddAircraft()
	{
		$name = Vars::POST('name');	
		$icao = Vars::POST('icao');	
		$fullname = Vars::POST('fullname');	
		$range = Vars::POST('range');	
		$weight = Vars::POST('weight');	
		$cruise = Vars::POST('cruise');	
		
		if($icao == '' || $name == '' || $fullname == '')
		{
			Template::Set('message', 'You must enter the ICAO, Name, and Full name');
			Template::Show('core_message.tpl');
			return;
		}
		
		if(!OperationsData::AddAircaft($icao, $name, $fullname, $range, $weight, $cruise))
		{
			if(DB::$errno == 1062) // Duplicate entry
				Template::Set('message', 'This aircraft already exists');
			else
				Template::Set('message', 'There was an error adding the aircraft');
		}
		else	
			Template::Set('message', 'The airport has been added');
		
		Template::Show('core_message.tpl');
		
	}
	
	function AddAirport()
	{
		$icao = Vars::POST('icao');	
		$name = Vars::POST('name');
		$country = Vars::POST('country');
		$lat = Vars::POST('lat');
		$long = Vars::POST('long');
		
		if($icao == '' || $name == '' || $country == '' || $lat == '' || $long == '')
		{
			Template::Set('message', 'Some fields were blank!');
			Template::Show('core_message.tpl');
			return;
		}
	
		if(!OperationsData::AddAirport($icao, $name, $country, $lat, $long))
		{
			if(DB::$errno == 1062) // Duplicate entry
				Template::Set('message', 'This airport has already been added');
			else
				Template::Set('message', 'There was an error adding the airport');
		}
		else	
			Template::Set('message', 'The airport has been added');
			
		Template::Show('core_message.tpl');
	}
	
	function ViewSchedules()
	{
		/*
			id
			code
			flightnum
			depicao
			arricao
			route
			aircraft
			distance
			deptime
			arrtime
			flighttime
			timesflown
		*/
		Template::Set('schedules', OperationsData::GetSchedules());
		
		Template::Show('ops_schedules.tpl');
	}
}
?>