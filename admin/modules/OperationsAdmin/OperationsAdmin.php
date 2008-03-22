<?php


class OperationsAdmin
{	
	function Controller()
	{
		switch(Vars::GET('admin'))
		{
			case 'addaircraft':
			
				Template::Set('title', 'Add Aircraft');
				Template::Set('action', 'addaircraft');
				Template::Show('ops_aircraftform.tpl');
				
				break;
			
			case 'editaircraft':
			
				$id = Vars::GET('id');
				
				Template::Set('aircraft', OperationsData::GetAircraftInfo($id));
				Template::Set('title', 'Edit Aircraft');
				Template::Set('action', 'editaircraft');
				Template::Show('ops_aircraftform.tpl');
				
				break;
								
			case 'aircraft':
			
				
				/* If they're adding an aircraft, go through this pain
				*/				 
				if(Vars::POST('action') == 'addaircraft')
				{
					$this->AddAircraft();
				}
				elseif(Vars::POST('action') == 'editaircraft')
				{
					$this->EditAircraft();
				}
			
				Template::Set('allaircraft', OperationsData::GetAllAircraft());
				Template::Show('ops_aircraftlist.tpl');
							
				break;
			
			case 'addairport':
				Template::Show('ops_add_airport.tpl');
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
				
			case 'addschedule':
				$this->AddScheduleForm();
				break;
				
			case 'schedules':
			
				/* These are loaded in popup box */
				if(Vars::GET('action') == 'viewroute')
				{
					$id = Vars::GET('id');
					return;	
				}
			
				if(Vars::POST('action') == 'addschedule')
				{
					$this->AddSchedule();
				}
			
				Template::Set('schedules', OperationsData::GetSchedules());
				Template::Show('ops_schedules.tpl');
				
				$this->AddScheduleForm();
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
			Template::Set('message', 'The aircraft has been added');
		
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
	
	function AddSchedule()
	{
		
		$code = Vars::POST('code');	
		$flightnum = Vars::POST('flightnum');	
		$leg = Vars::POST('leg');
		$depicao = Vars::POST('depicao');	
		$arricao = Vars::POST('arricao');
		$route = Vars::POST('route');
		$aircraft = Vars::POST('aircraft');
		$distance = Vars::POST('distance');
		$deptime = Vars::POST('deptime');
		$arrtime = Vars::POST('arrtime');
		$flighttime = Vars::POST('flighttime');
		
		if($code == '' || $flightnum == '' || $deptime == '' || $arrtime == ''
			|| $depicao == '' || $arricao == '')
		{
			Template::Set('message', 'All of the fields must be filled out');
			Template::Show('core_message.tpl');
			
			return;
		}
		
		//Add it in
		if(!OperationsData::AddSchedule($code, $flightnum, $leg, $depicao, $arricao, $route, $aircraft, 
										$distance, $deptime, $arrtime, $flighttime))
		{
			Template::Set('message', 'There was an error adding the schedule');
		}
		else
		{
			Template::Set('message', 'The schedule has been added');
		}
		
		Template::Show('core_message.tpl');
	}
	
	function AddScheduleForm()
	{		
		// Form the options list for the airports available to select
		//	Do it once here
		$allairports = OperationsData::GetAllAirports();
		$airports_options = '';
		foreach($allairports as $airport)
		{
			$airports_options .= '<option value="'.$airport->icao.'">'.$airport->icao.' ('.$airport->name.')</option>';
		}
		
		// Do the same as above for available aircraft
		$allaircraft = OperationsData::GetAllAircraft();
		$aircraft_options = '';
		foreach($allaircraft as $aircraft)
		{
			$aircraft_options .= '<option value="'.$aircraft->name.'">'.$aircraft->name.' ('.$aircraft->icao.')</option>';
		}
		
		Template::Set('airports', $airports_options);
		Template::Set('aircraft', $aircraft_options);
		
		Template::Show('ops_addschedule.tpl');
	}
	
	function EditAircraft()
	{
		$id = Vars::POST('id');
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
		
		if(!OperationsData::EditAircraft($id, $icao, $name, $fullname, $range, $weight, $cruise))
		{
			Template::Set('message', 'There was an error editing the aircraft');
		}
		else	
			Template::Set('message', 'The aircraft has been edited');
		
		Template::Show('core_message.tpl');
	}
}
?>