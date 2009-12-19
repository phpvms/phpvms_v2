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
				$this->set('sidebar', 'sidebar_airlines.tpl');
				break;
			case 'addaircraft':
			case 'aircraft':
				$this->set('sidebar', 'sidebar_aircraft.tpl');
				break;
			case 'airports':
				$this->set('sidebar', 'sidebar_airports.tpl');
				break;
			case '':
			case 'addschedule':
			case 'activeschedules':
			case 'inactiveschedules':
			case 'schedules':
				$this->set('sidebar', 'sidebar_schedules.tpl');
				break;
			case 'editschedule':
				$this->set('sidebar', 'sidebar_editschedule.tpl');
				break;
		}
	}
	
	public function index()
	{
		$this->schedules();
	}

	public function addaircraft()
	{
		$this->set('title', 'Add Aircraft');
		$this->set('action', 'addaircraft');
		$this->render('ops_aircraftform.tpl');	
	}
	
	public function editaircraft()
	{
		$id = $this->get->id;
				
		$this->set('aircraft', OperationsData::GetAircraftInfo($id));
		$this->set('title', 'Edit Aircraft');
		$this->set('action', 'editaircraft');
		$this->render('ops_aircraftform.tpl');	
	}
	
	public function addairline()
	{
		$this->set('title', 'Add Airline');
		$this->set('action', 'addairline');
		$this->render('ops_airlineform.tpl');
	}
	
	public function editairline()
	{
		$this->set('title', 'Edit Airline');
		$this->set('action', 'editairline');
		$this->set('airline', OperationsData::GetAirlineByID($this->get->id));
		
		$this->render('ops_airlineform.tpl');
	}
	
	public function calculatedistance($depicao='', $arricao='')
	{
		if($depicao == '')
			$depicao = $this->get->depicao;
		
		if($arricao == '')
			$arricao = $this->get->arricao;
			
			
		echo OperationsData::getAirportDistance($depicao, $arricao);
	}
	
	public function getfuelprice()
	{
		if(Config::Get('FUEL_GET_LIVE_PRICE') == false)
		{
			echo '<span style="color: red">Live fuel pricing is disabled!</span>';
			return;
		}
		
		$icao = $_GET['icao'];
		$price = FuelData::get_from_server($icao);
		
		if(is_bool($price) && $price === false)
		{
			echo '<span style="color: red">Live fuel pricing is not available for this airport</span>';
			return;
		}
		
		echo '<span style="color: #33CC00">OK! Found - current price: <strong>'.$price.'</strong></span>';
	}
	
	public function airlines()
	{
		if(isset($this->post->action))
		{
			if($this->post->action == 'addairline')
			{
				$this->add_airline_post();
			}
			elseif($this->post->action == 'editairline')
			{
				$this->edit_airline_post();
			}
		}
		
		$this->set('allairlines', OperationsData::GetAllAirlines());
		$this->render('ops_airlineslist.tpl');
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
	
		$this->set('allaircraft', OperationsData::GetAllAircraft());
		$this->render('ops_aircraftlist.tpl');
	}
	
	public function addairport()
	{
		$this->set('title', 'Add Airport');
		$this->set('action', 'addairport');

		$this->render('ops_airportform.tpl');
	}
	
	public function editairport()
	{
		$this->set('title', 'Edit Airport');
		$this->set('action', 'editairport');
		$this->set('airport', OperationsData::GetAirportInfo($this->get->icao));

		$this->render('ops_airportform.tpl');
	}
	
	public function airports()
	{
		/* If they're adding an airport, go through this pain
		*/
		if(isset($this->post->action))
		{
			switch($this->post->action)
			{
				case 'addairport':
					$this->add_airport_post();
					break;
				case 'editairport':
					$this->edit_airport_post();
					break;
			}
		}
							
		$this->set('airports', OperationsData::GetAllAirports());
		$this->render('ops_airportlist.tpl');
	}
	
	public function addschedule()
	{
		$this->set('title', 'Add Schedule');
		$this->set('action', 'addschedule');

        $this->set('allairlines', OperationsData::GetAllAirlines());
		$this->set('allaircraft', OperationsData::GetAllAircraft());
		$this->set('allairports', OperationsData::GetAllAirports());
		$this->set('flighttypes', Config::Get('FLIGHT_TYPES'));

		$this->render('ops_scheduleform.tpl');
	}
	
	public function editschedule()
	{
		$id = $this->get->id;

		$this->set('title', 'Edit Schedule');
		$this->set('schedule', SchedulesData::GetSchedule($id));
		
		$this->set('action', 'editschedule');

        $this->set('allairlines', OperationsData::GetAllAirlines());
		$this->set('allaircraft', OperationsData::GetAllAircraft());
		$this->set('allairports', OperationsData::GetAllAirports());
		$this->set('flighttypes', Config::Get('FLIGHT_TYPES'));
		
		$this->render('ops_scheduleform.tpl');
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
		if($this->get->action == 'filter')
		{
			$this->set('title', 'Filtered Schedules');
			
			if($this->get->type == 'flightnum')
			{
				$params = array('s.flightnum' => $this->get->query);
			}
			elseif($this->get->type == 'code')
			{
				$params = array('s.code' => $this->get->query);
			}
			elseif($this->get->type == 'aircraft')
			{
				$params = array('a.name' => $this->get->query);
			}
			elseif($this->get->type == 'depapt')
			{
				$params = array('s.depicao' => $this->get->query);
			}
			elseif($this->get->type == 'arrapt')
			{
				$params = array('s.arricao' => $this->get->query);
			}
			
			$this->set('schedules', SchedulesData::findSchedules($params));
			$this->render('ops_schedules.tpl');
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
	
		if(!isset($this->get->start) || $this->get->start == '')
		{
			$this->get->start = 0;
		}
		
		$num_per_page = 20;
		$start = $num_per_page * $this->get->start;
		
		if($type == 'schedules' || $type == 'activeschedules')
		{
			$params = array();
			$schedules = SchedulesData::findSchedules($params, $num_per_page, $start);
			
			$this->set('title', 'Viewing Active Schedules');
			$this->set('schedules', $schedules);
			
			if(count($schedules) >= $num_per_page)
			{
				$this->set('paginate', true);
				$this->set('start', $this->get->start+1);
				$this->set('prev', $this->get->start);
			}
		}
		else
		{
			$this->set('title', 'Viewing Inactive Schedules');
			$this->set('schedules', SchedulesData::findSchedules(array('s.enabled'=>0)));
		}
		
		$this->render('ops_schedules.tpl');
	}
		
	protected function add_airline_post()
	{
		$code = strtoupper($this->post->code);
		$name = $this->post->name;
		
		if($code == '' || $name == '')
		{
			$this->set('message', 'You must fill out all of the fields');
			$this->render('core_error.tpl');
			return;
		}
		
		if(OperationsData::GetAirlineByCode($code))
		{
			$this->set('message', 'An airline with this code already exists!');
			$this->render('core_error.tpl');
			return;
		}
		
		OperationsData::AddAirline($code, $name);
		
		if(DB::errno() != 0)
		{
			if(DB::errno() == 1062) // Duplicate entry
				$this->set('message', 'This airline has already been added');
			else
				$this->set('message', 'There was an error adding the airline');

            $this->render('core_error.tpl');
			return;
		}

		$this->set('message', 'Airline has been added!');
		$this->render('core_success.tpl');
	}
	
	protected function edit_airline_post()
	{
		$id = $this->post->id;
		$code = $this->post->code;
		$name = $this->post->name;
		
		if($code == '' || $name == '')
		{
			$this->set('message', 'Code and name cannot be blank');
			$this->render('core_error.tpl');
		}
		
		$prevairline = OperationsData::GetAirlineByCode($code);
		if($prevairline && $prevairline->id != $id)
		{
			$this->set('message', 'This airline with this code already exists!');
			$this->render('core_error.tpl');
			return;
		}
		
		if(isset($_POST['enabled']))
			$enabled = true;
		else
			$enabled = false;
			
		OperationsData::EditAirline($id, $code, $name, $enabled);
		
		if(DB::errno() != 0)
		{
			$this->set('message', 'There was an error editing the airline');
			$this->render('core_error.tpl');
			return false;
		}

		$this->set('message', 'The airline has been edited');
		$this->render('core_success.tpl');
	}
			
	protected function add_aircraft_post()
	{		
		if($this->post->icao == '' || $this->post->name == '' 
			|| $this->post->fullname == ''
			|| $this->post->registration == '')
		{
			$this->set('message', 'You must enter the ICAO, name, full name and the registration.');
			$this->render('core_error.tpl');
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
			$this->set('message', 'The aircraft registration must be unique');
			$this->render('core_error.tpl');
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
				$this->set('message', 'This aircraft already exists');
			else
				$this->set('message', 'There was an error adding the aircraft');

			$this->render('core_error.tpl');
			return false;
		}

		$this->set('message', 'The aircraft has been added');
		$this->render('core_success.tpl');
	}
	
	protected function add_airport_post()
	{
		
		if($this->post->icao == '' || $this->post->name == '' 
				|| $this->post->country == '' || $this->post->lat == '' || $this->post->long == '')
		{
			$this->set('message', 'Some fields were blank!');
			$this->render('core_error.tpl');
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
			'chartlink' => $this->post->chartlink,
			'fuelprice' => $this->post->fuelprice
			);
			
		OperationsData::AddAirport($data);
		
		if(DB::errno() != 0)
		{
			if(DB::$errno == 1062) // Duplicate entry
				$this->set('message', 'This airport has already been added');
			else
				$this->set('message', 'There was an error adding the airport');

			$this->render('core_error.tpl');
			return;
		}

		$this->set('message', 'The airport has been added');
		$this->render('core_success.tpl');
	}

	protected function edit_airport_post()
	{
		if($this->post->icao == '' || $this->post->name == '' 
				|| $this->post->country == '' || $this->post->lat == '' || $this->post->long == '')
		{
			$this->set('message', 'Some fields were blank!');
			$this->render('core_message.tpl');
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
			'chartlink' => $this->post->chartlink,
			'fuelprice' => $this->post->fuelprice
		);

		OperationsData::EditAirport($data);
		
		if(DB::errno() != 0)
		{
			$this->set('message', 'There was an error adding the airport: '.DB::$error);

			$this->render('core_error.tpl');
			return;
		}

		$this->set('message', $icao . ' has been edited');
		$this->render('core_success.tpl');
	}
	
	protected function add_schedule_post()
	{	
		if($this->post->code == '' || $this->post->flightnum == '' 
			|| $this->post->deptime == '' || $this->post->arrtime == ''
			|| $this->post->depicao == '' || $this->post->arricao == '')
		{
			$this->set('message', 'All of the fields must be filled out');
			$this->render('core_error.tpl');
			
			return;
		}
		
		# Check if the schedule exists
		$sched = SchedulesData::GetScheduleByFlight($this->post->code, $this->post->flightnum);
		if(is_object($sched))
		{
			$this->set('message', 'This schedule already exists!');
			$this->render('core_error.tpl');
			
			return;			
		}
		
		$enabled = ($this->post->enabled == 'on') ? true : false;
		
		# Check the distance
		if($this->post->distance == '' || $this->post->distance == 0)
		{
			$this->post->distance = OperationsData::getAirportDistance($this->post->depicao, $this->post->arricao);
		}
		
		# Format the flight level
		$this->post->flightlevel = str_replace(',', '', $this->post->flightlevel);
		$this->post->flightlevel = str_replace(' ', '', $this->post->flightlevel);

		$this->post->route = strtoupper($this->post->route);
	
		$data = array(	'code'=>$this->post->code,
						'flightnum'=>$this->post->flightnum,
						'depicao'=>$this->post->depicao,
						'arricao'=>$this->post->arricao,
						'route'=>$this->post->route,
						'aircraft'=>$this->post->aircraft,
						'flightlevel'=>$this->post->flightlevel,
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
            $this->set('message', 'There was an error adding the schedule, already exists DB error: '.DB::error());
			$this->render('core_error.tpl');
			return;
		}

        $this->set('message', 'The schedule "'.$this->post->code.$this->post->flightnum.'" has been added');
		$this->render('core_success.tpl');
	}

	protected function edit_schedule_post()
	{
		if($this->post->code == '' || $this->post->flightnum == '' 
			|| $this->post->deptime == '' || $this->post->arrtime == ''
			|| $this->post->depicao == '' || $this->post->arricao == '')
		{
			$this->set('message', 'All of the fields must be filled out');
			$this->render('core_error.tpl');
			
			return;
		}
		
		$enabled = ($this->post->enabled == 'on') ? true : false;
		$this->post->route = strtoupper($this->post->route);
		
		# Format the flight level
		$this->post->flightlevel = str_replace(',', '', $this->post->flightlevel);
		$this->post->flightlevel = str_replace(' ', '', $this->post->flightlevel);
		
		$data = array(	'id'=>$this->post->id,
						'code'=>$this->post->code,
						'flightnum'=>$this->post->flightnum,
						'depicao'=>$this->post->depicao,
						'arricao'=>$this->post->arricao,
						'route'=>$this->post->route,
						'aircraft'=>$this->post->aircraft,
						'flightlevel'=>$this->post->flightlevel,
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
			$this->set('message', 'There was an error editing the schedule: '.DB::error());
			$this->render('core_error.tpl');
			return;
		}

		$this->set('message', 'The schedule "'.$this->post->code.$this->post->flightnum.'" has been edited');
		$this->render('core_success.tpl');
	}

	protected function delete_schedule_post()
	{
		$id = $this->post->id;
		
		SchedulesData::DeleteSchedule($id);
		
        if(DB::errno() != 0)
		{
			$this->set('message', 'There was an error deleting the schedule');
			$this->render('core_error.tpl');
			return;
		}

		$this->set('message', 'The schedule has been deleted');
		$this->render('core_success.tpl');
	}

	protected function edit_aircraft_post()
	{
		if($this->post->id == '')
		{
			$this->set('message', 'Invalid ID specified');
			$this->render('core_error.tpl');
			return;
		}
		
		if($this->post->icao == '' || $this->post->name == '' 
			|| $this->post->fullname == '' || $this->post->registration == '')
		{
			$this->set('message', 'You must enter the ICAO, name, full name, and registration');
			$this->render('core_error.tpl');
			return;
		}
		
		$ac = OperationsData::CheckRegDupe($this->post->id, $this->post->registration);
		if($ac)
		{
			$this->set('message', 'This registration is already assigned to another active aircraft');
			$this->render('core_error.tpl');
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
			$this->set('message', 'There was an error editing the aircraft');
            $this->render('core_error.tpl');
			return;
		}

		$this->set('message', 'The aircraft "'.$this->post->registration.'" has been edited');
		$this->render('core_success.tpl');
	}
}