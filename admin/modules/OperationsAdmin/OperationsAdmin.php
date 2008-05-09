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
 

class OperationsAdmin
{	
	function HTMLHead()
	{
		Template::Set('sidebar', 'ops_sidebar.tpl');
	}
	
	function Controller()
	{
		switch(Vars::GET('admin'))
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
			
				$id = Vars::GET('id');
				
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
				
				if(Vars::POST('action') == 'addairline')
				{
					$this->AddAirline();
				}
				
				Template::Set('allairlines', OperationsData::GetAllAirlines());
				Template::Show('ops_airlineslist.tpl');
				
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
				if(Vars::POST('action') == 'addairport')
				{
					$this->AddAirport();
				}
				elseif(Vars::POST('action') == 'editairport')
				{
					$this->EditAirport();
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

				$id = Vars::GET('id');

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
				if(Vars::GET('action') == 'viewroute')
				{
					$id = Vars::GET('id');
					return;
				}

				if(Vars::POST('action') == 'addschedule')
				{
					$this->AddSchedule();
				}
				elseif(Vars::POST('action') == 'editschedule')
				{
					$this->EditSchedule(); //print_r($_POST);
				}
				elseif(Vars::POST('action') == 'deleteschedule')
				{
					$this->DeleteSchedule();
				}
			
				Template::Set('schedules', SchedulesData::GetSchedules());
				Template::Show('ops_schedules.tpl');
				
				//$this->AddScheduleForm();
				break;
		}
	}
	
	function AddAirline()
	{
		$code = Vars::POST('code');
		$name = Vars::POST('name');
		
		if($code == '' || $name == '')
		{
			Template::Set('message', 'You must fill out all of the fields');
			Template::Show('core_message.tpl');
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

			Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'The aircraft has been added');

		Template::Show('core_success.tpl');
	}
	
	function AddAirport()
	{
		$icao = Vars::POST('icao');
		$name = Vars::POST('name');
		$country = Vars::POST('country');
		$lat = Vars::POST('lat');
		$long = Vars::POST('long');
		$hub = Vars::POST('hub');

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
        $icao = Vars::POST('icao');
		$name = Vars::POST('name');
		$country = Vars::POST('country');
		$lat = Vars::POST('lat');
		$long = Vars::POST('long');
		$hub = Vars::POST('hub');

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
		if(!SchedulesData::AddSchedule($code, $flightnum, $leg, $depicao, $arricao, $route, $aircraft,
										$distance, $deptime, $arrtime, $flighttime))
		{
            Template::Set('message', 'There was an error adding the schedule');
			Template::Show('core_error.tpl');
			return;
		}

        Template::Set('message', 'The schedule has been added');
		Template::Show('core_success.tpl');
	}

	function EditSchedule()
	{
		$scheduleid = Vars::POST('id');
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

		if(!SchedulesData::EditSchedule($scheduleid, $code, $flightnum, $leg, $depicao, $arricao, $route, $aircraft,
										$distance, $deptime, $arrtime, $flighttime))
		{
			Template::Set('message', 'There was an error editing the schedule');
			Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'The schedule has been edited');
		Template::Show('core_success.tpl');
	}

	function DeleteSchedule()
	{
		$id = Vars::POST('id');


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