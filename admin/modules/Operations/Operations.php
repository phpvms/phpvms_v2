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
		switch($this->get->page)
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
			case 'addschedule':
			case 'activeschedules':
			case 'inactiveschedules':
			case 'schedules':
				Template::Set('sidebar', 'sidebar_schedules.tpl');
				break;
			case 'editschedule':
				Template::Set('sidebar', 'sidebar_editschedule.tpl');
				break;
		}
	}

	function Controller()
	{
		switch($this->get->page)
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
				
			case 'editairline':
				
				
				Template::Set('title', 'Edit Airline');
				Template::Set('action', 'editairline');
				Template::Set('airline', OperationsData::GetAirlineByID($this->get->id));
				
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
				if($this->post->action == 'editairline')
				{
					$this->EditAirline();
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

			case 'activeschedules':
			case 'inactiveschedules':
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
			
				if($this->get->page == 'schedules' || $this->get->page == 'activeschedules')
				{
					Template::Set('title', 'Viewing Active Schedules');
					Template::Set('schedules', SchedulesData::GetSchedules('', true));
				}
				else
				{
					Template::Set('title', 'Viewing Inactive Schedules');
					Template::Set('schedules', SchedulesData::GetInactiveSchedules());
				}
				
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
		
		if(OperationsData::GetAirlineByCode($code))
		{
			Template::Set('message', 'An airline with this code already exists!');
			Template::Show('core_error.tpl');
			return;
		}
		
		OperationsData::AddAirline($code, $name);
		
		if(DB::errno() != 0)
		{
			if(DB::errno() == 1062) // Duplicate entry
				Template::Set('message', 'This airline has already been added');
			else
				Template::Set('message', 'There was an error adding the airline');

            Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'Airline has been added!');
		Template::Show('core_success.tpl');
	}
	
	function EditAirline()
	{
		$id = $this->post->id;
		$code = $this->post->code;
		$name = $this->post->name;
		
		if($code == '' || $name == '')
		{
			Template::Set('message', 'Code and name cannot be blank');
			Template::Show('core_error.tpl');
		}
		
		$prevairline = OperationsData::GetAirlineByCode($code);
		if($prevairline && $prevairline->id != $id)
		{
			Template::Set('message', 'This airline with this code already exists!');
			Template::Show('core_error.tpl');
			return;
		}
		
		if(isset($_POST['enabled']))
			$enabled = true;
		else
			$enabled = false;
			
		OperationsData::EditAirline($id, $code, $name, $enabled);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error editing the airline');
			Template::Show('core_error.tpl');
			return false;
		}

		Template::Set('message', 'The airline has been edited');
		Template::Show('core_success.tpl');
	}
			
	function AddAircraft()
	{		
		if($this->post->icao == '' || $this->post->name == '' || $this->post->fullname == ''
			|| $this->post->registration == '')
		{
			Template::Set('message', 'You must enter the ICAO, name, full name and the registration.');
			Template::Show('core_error.tpl');
			return;
		}
		
		OperationsData::AddAircaft($this->post->icao, $this->post->name, $this->post->fullname, 
					$this->post->registration, $this->post->downloadlink, $this->post->imagelink,
					$this->post->range, $this->post->weight, $this->post->cruise);
		
		if(DB::errno() != 0)
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
	
		OperationsData::AddAirport($icao, $name, $country, $lat, $long, $hub);
		
		if(DB::errno() != 0)
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

		OperationsData::EditAirport($icao, $name, $country, $lat, $long, $hub);
		
		if(DB::errno() != 0)
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

		# Make sure it's a valid leg
		if($leg == '' || $leg == '0')
			$leg = 1;
			
			
		# Add it in
		$ret = SchedulesData::AddSchedule($code, $flightnum, $leg, $depicao, $arricao, $route, $aircraft,
										$distance, $deptime, $arrtime, $flighttime, $notes, $enabled);
			
		if(DB::errno() != 0 && $ret == false)
		{
            Template::Set('message', 'There was an error adding the schedule, already exists DB error: '.DB::error());
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
			Template::Show('core_error.tpl');

			return;
		}

		SchedulesData::EditSchedule($scheduleid, $code, $flightnum, $leg, $depicao, $arricao, $route, $aircraft,
										$distance, $deptime, $arrtime, $flighttime, $notes, $enabled);
										
		if(DB::errno() != 0)
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
		
		SchedulesData::DeleteSchedule($id);
		
        if(DB::errno() != 0)
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
		if($this->post->id == '')
		{
			Template::Set('message', 'Invalid ID specified');
			Template::Show('core_error.tpl');
			return;
		}
		
		if($this->post->icao == '' || $this->post->name == '' 
			|| $this->post->fullname == '' || $this->post->registration == '')
		{
			Template::Set('message', 'You must enter the ICAO, name, full name, and registration');
			Template::Show('core_error.tpl');
			return;
		}

		OperationsData::EditAircraft($this->post->id, $this->post->icao, $this->post->name, 
					$this->post->fullname, $this->post->registration, $this->post->downloadlink,
					$this->post->imagelink, $this->post->range, $this->post->weight, $this->post->cruise);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error editing the aircraft');
            Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'The aircraft has been edited');
		Template::Show('core_success.tpl');
	}
}