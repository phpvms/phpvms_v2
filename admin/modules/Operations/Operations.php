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
	public function HTMLHead()
	{
		switch($this->get->page)
		{
			case 'airlines':
				Template::Set('sidebar', 'sidebar_airlines.tpl');
				break;
			case 'addaircraft':
			case 'aircraft':
				Template::Set('sidebar', 'sidebar_aircraft.tpl');
				break;
			case 'airports':
				Template::Set('sidebar', 'sidebar_airports.tpl');
				break;
			case '':
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

	public function addaircraft()
	{
		Template::Set('title', 'Add Aircraft');
		Template::Set('action', 'addaircraft');
		Template::Show('ops_aircraftform.tpl');	
	}
	
	public function editaircraft()
	{
		$id = $this->get->id;
				
		Template::Set('aircraft', OperationsData::GetAircraftInfo($id));
		Template::Set('title', 'Edit Aircraft');
		Template::Set('action', 'editaircraft');
		Template::Show('ops_aircraftform.tpl');	
	}
	
	public function addairline()
	{
		Template::Set('title', 'Add Airline');
		Template::Set('action', 'addairline');
		Template::Show('ops_airlineform.tpl');
	}
	
	public function editairline()
	{
		Template::Set('title', 'Edit Airline');
		Template::Set('action', 'editairline');
		Template::Set('airline', OperationsData::GetAirlineByID($this->get->id));
		
		Template::Show('ops_airlineform.tpl');
	}
	
	
	public function calculateddistance()
	{
		echo OperationsData::getAirportDistance($this->get->depicao, $this->get->arricao);
	}
	
	
	public function airlines()
	{
		if($this->post->action == 'addairline')
		{
			$this->add_airline_post();
		}
		if($this->post->action == 'editairline')
		{
			$this->edit_airline_post();
		}
		
		Template::Set('allairlines', OperationsData::GetAllAirlines());
		Template::Show('ops_airlineslist.tpl');
	}
	
	public function aircraft()
	{
		/* If they're adding an aircraft, go through this pain
		*/
		switch($this->post->action)
		{
			case 'addaircraft':
				
				$this->add_aircraft_post();
				
				break;
			
			case 'editaircraft':
				
				$this->edit_aircraft_post();
				
				break;
		}
	
		Template::Set('allaircraft', OperationsData::GetAllAircraft());
		Template::Show('ops_aircraftlist.tpl');
	}
	
	public function addairport()
	{
		Template::Set('title', 'Add Airport');
		Template::Set('action', 'addairport');

		Template::Show('ops_airportform.tpl');
	}
	
	public function editairport()
	{
		Template::Set('title', 'Edit Airport');
		Template::Set('action', 'editairport');
		Template::Set('airport', OperationsData::GetAirportInfo($this->get->icao));

		Template::Show('ops_airportform.tpl');
	}
	
	public function airports()
	{
		/* If they're adding an airport, go through this pain
		*/
		switch($this->post->action)
		{
			case 'addairport':
				
				$this->add_airport_post();
				
				break;
			
			case 'editairport':
				
				$this->edit_airport_post();
				
				break;
		}
						
		Template::Set('airports', OperationsData::GetAllAirports());
		
		Template::Show('ops_airportlist.tpl');
	}
	
	public function addschedule()
	{
		Template::Set('title', 'Add Schedule');
		Template::Set('action', 'addschedule');

        Template::Set('allairlines', OperationsData::GetAllAirlines());
		Template::Set('allaircraft', OperationsData::GetAllAircraft());
		Template::Set('allairports', OperationsData::GetAllAirports());
		Template::Set('flighttypes', Config::Get('FLIGHT_TYPES'));

		Template::Show('ops_scheduleform.tpl');
	}
	
	public function editschedule()
	{
		$id = $this->get->id;

		Template::Set('title', 'Edit Schedule');
		Template::Set('schedule', SchedulesData::GetSchedule($id));
		
		Template::Set('action', 'editschedule');

        Template::Set('allairlines', OperationsData::GetAllAirlines());
		Template::Set('allaircraft', OperationsData::GetAllAircraft());
		Template::Set('allairports', OperationsData::GetAllAirports());
		Template::Set('flighttypes', Config::Get('FLIGHT_TYPES'));
		
		Template::Show('ops_scheduleform.tpl');
	}
	
	public function activeschedules()
	{
		$this->schedules('activeschedules');
	}
	
	public function inactiveschedules()
	{
		$this->schedules('inactiveschedules');
	}
	
	public function schedules($type='activeschedules')
	{
		/* These are loaded in popup box */
		if($this->get->action == 'viewroute')
		{
			$id = $this->get->id;
			return;
		}
		
		switch($this->post->action)
		{
			case 'addschedule':
				$this->add_schedule_post();
				break;
				
			case 'editschedule':
				$this->edit_schedule_post();
				break;
				
			case 'deleteschedule':
				$this->delete_schedule_post();
				break;
		}
	
		if($type == 'schedules' || $type == 'activeschedules')
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
	}
		
	protected function add_airline_post()
	{
		$code = strtoupper($this->post->code);
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
	
	protected function edit_airline_post()
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
			
	protected function add_aircraft_post()
	{		
		if($this->post->icao == '' || $this->post->name == '' 
			|| $this->post->fullname == ''
			|| $this->post->registration == '')
		{
			Template::Set('message', 'You must enter the ICAO, name, full name and the registration.');
			Template::Show('core_error.tpl');
			return;
		}
		
		if($this->post->enabled == '1')
			$this->post->enabled = true;
		else
			$this->post->enabled = false;
			
		# Check aircraft registration, make sure it's not a duplicate
		
		$ac = OperationsData::GetAircraftByReg($this->post->registration);
		if($ac)
		{
			Template::Set('message', 'The aircraft registration must be unique');
			Template::Show('core_error.tpl');
			return;
		}
		
		$data = array(	'icao'=>$this->post->icao,
						'name'=>$this->post->name,
						'fullname'=>$this->post->fullname,
						'registration'=>$this->post->registration,
						'downloadlink'=>$this->post->downloadlink,
						'imagelink'=>$this->post->imagelink,
						'range'=>$this->post->range,
						'weight'=>$this->post->weight,
						'cruise'=>$this->post->cruise,
						'maxpax'=>$this->post->maxpax,
						'maxcargo'=>$this->post->maxcargo,
						'enabled'=>$this->post->enabled);
						
			
		OperationsData::AddAircaft($data);
		
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
	
	protected function add_airport_post()
	{
		
		if($this->post->icao == '' || $this->post->name == '' 
				|| $this->post->country == '' || $this->post->lat == '' || $this->post->long == '')
		{
			Template::Set('message', 'Some fields were blank!');
			Template::Show('core_error.tpl');
			return;
		}

		if($this->post->hub == 'true')
			$this->post->hub = true;
		else
			$this->post->hub = false;
	
		$data = array(
			'icao' => $this->post->icao,
			'name' => $this->post->name,
			'country' => $this->post->country,
			'lat' => $this->post->lat,
			'lng' => $this->post->long,
			'hub' => $this->post->hub,
			'fuelprice' => $this->post->fuelprice
			);
			
		OperationsData::AddAirport($data);
		
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

	protected function edit_airport_post()
	{
		if($this->post->icao == '' || $this->post->name == '' 
				|| $this->post->country == '' || $this->post->lat == '' || $this->post->long == '')
		{
			Template::Set('message', 'Some fields were blank!');
			Template::Show('core_message.tpl');
			return;
		}

		if($this->post->hub == 'true')
			$this->post->hub = true;
		else
			$this->post->hub = false;
			
			
		$data = array(
			'icao' => $this->post->icao,
			'name' => $this->post->name,
			'country' => $this->post->country,
			'lat' => $this->post->lat,
			'lng' => $this->post->long,
			'hub' => $this->post->hub,
			'fuelprice' => $this->post->fuelprice
		);

		OperationsData::EditAirport($data);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error adding the airport: '.DB::$error);

			Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', $icao . ' has been edited');
		Template::Show('core_success.tpl');
	}
	
	protected function add_schedule_post()
	{	
		if($this->post->code == '' || $this->post->flightnum == '' 
			|| $this->post->deptime == '' || $this->post->arrtime == ''
			|| $this->post->depicao == '' || $this->post->arricao == '')
		{
			Template::Set('message', 'All of the fields must be filled out');
			Template::Show('core_error.tpl');
			
			return;
		}
		
		# Check if the schedule exists
		$sched = SchedulesData::GetScheduleByFlight($this->post->code, $this->post->flightnum);
		if(is_object($sched))
		{
			Template::Set('message', 'This schedule already exists!');
			Template::Show('core_error.tpl');
			
			return;			
		}
		
		$enabled = ($this->post->enabled == 'on') ? true : false;
		
		# Check the distance
		if($this->post->distance == '' || $this->post->distance == 0)
		{
			$this->post->distance = OperationsData::getAirportDistance($this->post->depicao, $this->post->arricao);
		}

		$this->post->route = strtoupper($this->post->route);
	
		$data = array(	'code'=>$this->post->code,
						'flightnum'=>$this->post->flightnum,
						'depicao'=>$this->post->depicao,
						'arricao'=>$this->post->arricao,
						'route'=>$this->post->route,
						'aircraft'=>$this->post->aircraft,
						'distance'=>$this->post->distance,
						'deptime'=>$this->post->deptime,
						'arrtime'=>$this->post->arrtime,
						'flighttime'=>$this->post->flighttime,
						'daysofweek'=>implode('', $_POST['daysofweek']),
						'maxload'=>$this->post->maxload,
						'price'=>$this->post->price,
						'flighttype'=>$this->post->flighttype,
						'notes'=>$this->post->notes,
						'enabled'=>$enabled);
				
		# Add it in
		$ret = SchedulesData::AddSchedule($data);
			
		if(DB::errno() != 0 && $ret == false)
		{
            Template::Set('message', 'There was an error adding the schedule, already exists DB error: '.DB::error());
			Template::Show('core_error.tpl');
			return;
		}

        Template::Set('message', 'The schedule "'.$this->post->code.$this->post->flightnum.'" has been added');
		Template::Show('core_success.tpl');
	}

	protected function edit_schedule_post()
	{
		if($this->post->code == '' || $this->post->flightnum == '' 
			|| $this->post->deptime == '' || $this->post->arrtime == ''
			|| $this->post->depicao == '' || $this->post->arricao == '')
		{
			Template::Set('message', 'All of the fields must be filled out');
			Template::Show('core_message.tpl');
			
			return;
		}
		
		$enabled = ($this->post->enabled == 'on') ? true : false;
		$this->post->route = strtoupper($this->post->route);
		
		$data = array(	'scheduleid'=>$this->post->id,
						'code'=>$this->post->code,
						'flightnum'=>$this->post->flightnum,
						'depicao'=>$this->post->depicao,
						'arricao'=>$this->post->arricao,
						'route'=>$this->post->route,
						'aircraft'=>$this->post->aircraft,
						'distance'=>$this->post->distance,
						'deptime'=>$this->post->deptime,
						'arrtime'=>$this->post->arrtime,
						'flighttime'=>$this->post->flighttime,
						'daysofweek'=>implode('', $_POST['daysofweek']),
						'maxload'=>$this->post->maxload,
						'price'=>$this->post->price,
						'flighttype'=>$this->post->flighttype,
						'notes'=>$this->post->notes,
						'enabled'=>$enabled);
		
		$val = SchedulesData::EditSchedule($data);
		if(!$val)
		{
			Template::Set('message', 'There was an error editing the schedule: '.DB::error());
			Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'The schedule "'.$this->post->code.$this->post->flightnum.'" has been edited');
		Template::Show('core_success.tpl');
	}

	protected function delete_schedule_post()
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

	protected function edit_aircraft_post()
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
		
		$ac = OperationsData::CheckRegDupe($this->post->id, $this->post->registration);
		if($ac)
		{
			Template::Set('message', 'This registration is already assigned to another active aircraft');
			Template::Show('core_error.tpl');
			return;
		}
		
		if($this->post->enabled == '1')
			$this->post->enabled = true;
		else
			$this->post->enabled = false;
			
		$data = array(	
			'id' => $this->post->id,
			'icao'=>$this->post->icao,
			'name'=>$this->post->name,
			'fullname'=>$this->post->fullname,
			'registration'=>$this->post->registration,
			'downloadlink'=>$this->post->downloadlink,
			'imagelink'=>$this->post->imagelink,
			'range'=>$this->post->range,
			'weight'=>$this->post->weight,
			'cruise'=>$this->post->cruise,
			'maxpax'=>$this->post->maxpax,
			'maxcargo'=>$this->post->maxcargo,
			'enabled'=>$this->post->enabled
		);
			
		OperationsData::EditAircraft($data);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error editing the aircraft');
            Template::Show('core_error.tpl');
			return;
		}

		Template::Set('message', 'The aircraft "'.$this->post->registration.'" has been edited');
		Template::Show('core_success.tpl');
	}
}