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
 * @package module_admin_operations
 */
 

class Operations extends CodonModule
{
	function HTMLHead()
	{
		switch($this->get->admin)
		{
			case 'airlines':
				Template::Set('sidebar', 'sidebar_airlines.tpl');
				break;
			case 'aircraft':
				Template::Set('sidebar', 'sidebar_aircraft.tpl');
				break;
			case 'airports':
				Template::Set('sidebar', 'sidebar_airports.tpl');
				break;
			case 'schedules':
				Template::Set('sidebar', 'sidebar_schedules.tpl');
				break;
		}
	}

	function Controller()
	{
		switch($this->get->admin)
		{
			
			/**
			 * These are for the popup boxes
			 */
			
			/* Aircraft Operations
			 */
			case 'addaircraft':
			
				Template::Set('title', 'Add Aircraft');
				Template::Set('action', 'addaircraft');
				Template::Show('ops_aircraftform.tpl');
				
				break;

			case 'editaircraft':
			
				$id = $this->get->id;
				
				Template::Set('aircraft', OperationsData::GetAircraftInfo($id));
				Template::Set('title', 'Edit Aircraft');
				Template::Set('action', 'editaircraft');
				Template::Show('ops_aircraftform.tpl');
				
				break;

			
			/* Aircraft Operations
			 */
			case 'addairline':
				
				Template::Set('title', 'Add Airline');
				Template::Set('action', 'addairline');
				Template::Show('ops_airlineform.tpl');
				break;
				
			
			/**
			 * These are the main form
			 */
			
			case 'airlines':
				
				if($this->post->action == 'addairline')
				{
					$this->AddAirline();
				}
				
				Template::Set('allairlines', OperationsData::GetAllAirlines());
				Template::Show('ops_airlineslist.tpl');
				
				break;
				
			case 'aircraft':
			
				
				/* If they're adding an aircraft, go through this pain
				*/
				switch($this->post->action)
				{
					case 'addaircraft':
						
						$this->AddAircraft();
						
						break;
					
					case 'editaircraft':
						
						$this->EditAircraft();
						
						break;
				}
			
				Template::Set('allaircraft', OperationsData::GetAllAircraft());
				Template::Show('ops_aircraftlist.tpl');
							
				break;
			
			case 'addairport':
				Template::Set('title', 'Add Airport');
				Template::Set('action', 'addairport');

				Template::Show('ops_airportform.tpl');
				break;

			case 'editairport':
                Template::Set('title', 'Edit Airport');
				Template::Set('action', 'editairport');
				Template::Set('airport', OperationsData::GetAirportInfo(Vars::GET('icao')));

				Template::Show('ops_airportform.tpl');
				break;

			case 'airports':
				
				/* If they're adding an airport, go through this pain
				*/
				switch($this->post->action)
				{
					case 'addairport':
						
						$this->AddAirport();
						
						break;
					
					case 'editairport':
						
						$this->EditAirport();
						
						break;
				}
								
				Template::Set('airports', OperationsData::GetAllAirports());
				Template::Show('ops_airportlist.tpl');
				
				break;
				
			case 'addschedule':
			
				Template::Set('title', 'Add Schedule');
				Template::Set('action', 'addschedule');

                Template::Set('allairlines', OperationsData::GetAllAirlines());
				Template::Set('allaircraft', OperationsData::GetAllAircraft());
				Template::Set('allairports', OperationsData::GetAllAirports());

				Template::Show('ops_scheduleform.tpl');

				break;

            case 'editschedule':

				$id = $this->get->id;

				Template::Set('title', 'Edit Schedule');
				Template::Set('schedule', SchedulesData::GetSchedule($id));
				Template::Set('action', 'editschedule');

                Template::Set('allairlines', OperationsData::GetAllAirlines());
				Template::Set('allaircraft', OperationsData::GetAllAircraft());
				Template::Set('allairports', OperationsData::GetAllAirports());

				Template::Show('ops_scheduleform.tpl');

				break;

			case 'schedules':

				/* These are loaded in popup box */
				if($this->get->action == 'viewroute')
				{
					$id = $this->get->id;
					return;
				}
				
				switch($this->post->action)
				{
					case 'addschedule':
						$this->AddSchedule();
						break;
						
					case 'editschedule':
						$this->EditSchedule();
						break;
						
					case 'deleteschedule':
						$this->DeleteSchedule();
						break;
				}
			
				Template::Set('schedules', SchedulesData::GetSchedules('', false));
				Template::Show('ops_schedules.tpl');

				break;
		}
	}
	
	function AddAirline()
	{
		$code = $this->post->code;
		$name = $this->post->name;
		
		if($code == '' || $name == '')
		{
			Template::Set('message', 'You must fill out all of the fields');
			Template::Show('core_error.tpl');
			return;
		}
	
		if(!OperationsData::AddAirline($code, $name))
		{
			if(DB::$errno == 1062) // Duplicate entry
				Template::Set('message', 'This airline has already been added');
			else
				Template::Set('message', 'There was an error adding the airline');

            Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'Airline has been added!');
		Template::Show('core_success.tpl');
	}
			
	function AddAircraft()
	{
		$name = $this->post->name;
		$icao = $this->post->icao;
		$fullname = $this->post->fullname;
		$range = $this->post->range;
		$weight = $this->post->weight;
		$cruise = $this->post->cruise;
		
		if($icao == '' || $name == '' || $fullname == '')
		{
			Template::Set('message', 'You must enter the ICAO, Name, and Full name');
			Template::Show('core_error.tpl');
			return;
		}
		
		if(!OperationsData::AddAircaft($icao, $name, $fullname, $range, $weight, $cruise))
		{
			if(DB::$errno == 1062) // Duplicate entry
				Template::Set('message', 'This aircraft already exists');
			else
				Template::Set('message', 'There was an error adding the aircraft');

			Template::Show('core_error.tpl');
			return false;
		}

		Template::Set('message', 'The aircraft has been added');
		Template::Show('core_success.tpl');
	}
	
	function AddAirport()
	{
		$icao = $this->post->icao;
		$name = $this->post->name;
		$country = $this->post->country;
		$lat = $this->post->lat;
		$long = $this->post->long;
		$hub = $this->post->hub;
		
		echo 'i am here';

		if($icao == '' || $name == '' || $country == '' || $lat == '' || $long == '')
		{
			Template::Set('message', 'Some fields were blank!');
			Template::Show('core_error.tpl');
			return;
		}

        if($hub == 'true')
			$hub = true;
		else
			$hub = false;
	
		if(!OperationsData::AddAirport($icao, $name, $country, $lat, $long, $hub))
		{
			if(DB::$errno == 1062) // Duplicate entry
				Template::Set('message', 'This airport has already been added');
			else
				Template::Set('message', 'There was an error adding the airport');

			Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'The airport has been added');

		Template::Show('core_success.tpl');
	}

	function EditAirport()
	{
       $icao = $this->post->icao;
		$name = $this->post->name;
		$country = $this->post->country;
		$lat = $this->post->lat;
		$long = $this->post->long;
		$hub = $this->post->hub;

		if($icao == '' || $name == '' || $country == '' || $lat == '' || $long == '')
		{
			Template::Set('message', 'Some fields were blank!');
			Template::Show('core_message.tpl');
			return;
		}

		if($hub == 'true')
			$hub = true;
		else
			$hub = false;

		if(!OperationsData::EditAirport($icao, $name, $country, $lat, $long, $hub))
		{
			Template::Set('message', 'There was an error adding the airport: '.DB::$error);

			Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', $icao . ' has been edited');
		Template::Show('core_success.tpl');
	}
	
	function AddSchedule()
	{
		
		$code = $this->post->code;
		$flightnum = $this->post->flightnum;
		$leg = $this->post->leg;
		$depicao = $this->post->depicao;
		$arricao = $this->post->arricao;
		$route = $this->post->route;
		$aircraft = $this->post->aircraft;
		$distance = $this->post->distance;
		$deptime = $this->post->deptime;
		$arrtime =$this->post->arrtime;
		$flighttime = $this->post->flighttime;
		$notes = $this->post->notes;
		$enabled = (isset($_POST['enabled'])) ? true : false;

		if($code == '' || $flightnum == '' || $deptime == '' || $arrtime == ''
			|| $depicao == '' || $arricao == '')
		{
			Template::Set('message', 'All of the fields must be filled out');
			Template::Show('core_message.tpl');
			
			return;
		}

		//Add it in
		if(!SchedulesData::AddSchedule($code, $flightnum, $leg, $depicao, $arricao, $route, $aircraft,
										$distance, $deptime, $arrtime, $flighttime, $notes, $enabled))
		{
            Template::Set('message', 'There was an error adding the schedule: '.DB::error());
			Template::Show('core_error.tpl');
			return;
		}

        Template::Set('message', 'The schedule has been added');
		Template::Show('core_success.tpl');
	}

	function EditSchedule()
	{
		$scheduleid = $this->post->id;
   		$code = $this->post->code;
		$flightnum = $this->post->flightnum;
		$leg = $this->post->leg;
		$depicao = $this->post->depicao;
		$arricao = $this->post->arricao;
		$route = $this->post->route;
		$aircraft = $this->post->aircraft;
		$distance = $this->post->distance;
		$deptime = $this->post->deptime;
		$arrtime =$this->post->arrtime;
		$flighttime = $this->post->flighttime;
		$notes = $this->post->notes;
		$enabled = (isset($_POST['enabled'])) ? true : false;
		
		if($code == '' || $flightnum == '' || $deptime == '' || $arrtime == ''
			|| $depicao == '' || $arricao == '')
		{
			Template::Set('message', 'All of the fields must be filled out');
			Template::Show('core_message.tpl');

			return;
		}

		if(!SchedulesData::EditSchedule($scheduleid, $code, $flightnum, $leg, $depicao, $arricao, $route, $aircraft,
										$distance, $deptime, $arrtime, $flighttime, $notes, $enabled))
		{
			Template::Set('message', 'There was an error editing the schedule: '.DB::error());
			Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'The schedule has been edited');
		Template::Show('core_success.tpl');
	}

	function DeleteSchedule()
	{
		$id = $this->post->id;
		
        if(!SchedulesData::DeleteSchedule($id))
		{
			Template::Set('message', 'There was an error deleting the schedule');
			Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'The schedule has been deleted');
		Template::Show('core_success.tpl');
	}

	function EditAircraft()
	{
		$id = $this->post->id;
		$name = $this->post->name;
		$icao = $this->post->icao;
		$fullname = $this->post->fullname;
		$range = $this->post->range;
		$weight = $this->post->weight;
		$cruise = $this->post->cruise;
		
		if($icao == '' || $name == '' || $fullname == '')
		{
			Template::Set('message', 'You must enter the ICAO, Name, and Full name');
			Template::Show('core_error.tpl');
			return;
		}

		if(!OperationsData::EditAircraft($id, $icao, $name, $fullname, $range, $weight, $cruise))
		{
			Template::Set('message', 'There was an error editing the aircraft');
            Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'The aircraft has been edited');
		Template::Show('core_success.tpl');
	}
}
?>