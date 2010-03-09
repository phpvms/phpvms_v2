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

class FSFK extends CodonModule
{
	
	public function __call($name, $args)
	{
		$this->log(print_r($_REQUEST, true), 'fsfk');
		$this->log(serialize($_REQUEST), 'fsfk');
	}
	
	public function index()
	{
		
	}
	
	
	/**
	 * File PIREP
	 *
	 */
	public function pirep()
	{
		$data = "<?xml version=\"1.0\" encoding='UTF-8'?>".trim(utf8_encode($_REQUEST['DATA2']));
		$xml = simplexml_load_string($data);
		
		$this->log($data, 'fsfk');
		$this->log(print_r($xml, true), 'fsfk');
		$this->log(serialize($xml), 'fsfk');
		
		preg_match('/^([A-Za-z]*)(\d*)/', $xml->PilotID, $matches);
		$code = $matches[1];
		$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
		
		$flightinfo = SchedulesData::getProperFlightNum($xml->FlightNumber);
		$code = $flightinfo['code'];
		$flightnum = $flightinfo['flightnum'];
		
		# Extract the ICAO of the airport
		$depicao = strtoupper(substr($xml->OriginICAO, 0, 4));
		$arricao = strtoupper(substr($xml->DestinationICAO, 0, 4));
		
		if(!OperationsData::GetAirportInfo($depicao))
		{
			OperationsData::RetrieveAirportInfo($depicao);
		}
		
		if(!OperationsData::GetAirportInfo($arricao))
		{
			OperationsData::RetrieveAirportInfo($arricao);
		}
		
		$load = (string) $xml->Passenger;
		if($load == '' || $load == 0)
			$load = (string) $xml->Cargo;
			
		$flighttime = str_replace(':', '.', (string) $xml->BlockTime);
		
		# Get the proper aircraft
		$ac = OperationsData::GetAircraftByReg((string) $xml->AircraftTailNumber);
		if(!$ac)
		{
			$aircraft = 0;
		}
		else
		{
			$aircraft = $ac->id;
			unset($ac);
		}
		
		/* Process the report, to put into the log */

		$log = '';
		$images = '';
		$rawdata = array();
		
		# Setup the base things
		$rawdata['FLIGHTMAPS'] = array();
		$rawdata['FLIGHTDATA'] = array();
		
		foreach($xml as $key => $value)
		{
			/* Add the map images in */
			if($key == 'FLIGHTMAPS')
			{
				
				$img = (string)$xml->FLIGHTMAPS->FlightMapJPG;
				
				if($img)
					$rawdata['FLIGHTMAPS']['FlightMap'] = $img;
				
				$img = (string)$xml->FLIGHTMAPS->FlightMapWeatherJPG;
				if($img)
					$rawdata['FLIGHTMAPS']['FlightMapWeather'] = $img;
					
				$img = (string)$xml->FLIGHTMAPS->FlightMapTaxiOutJPG;
				if($img)
					$rawdata['FLIGHTMAPS']['FlightMapTaxiOut'] = $img;
					
				$img = (string)$xml->FLIGHTMAPS->FlightMapTaxiInJPG;
				if($img)
					$rawdata['FLIGHTMAPS']['FlightMapTaxiIn'] = $img;
				
				$img = (string)$xml->FLIGHTMAPS->FlightMapVerticalProfileJPG;
				if($img)
					$rawdata['FLIGHTMAPS']['FlightMapVerticalProfile'] = $img;
					
				$img = (string)$xml->FLIGHTMAPS->FlightMapLandingProfileJPG;
				if($img)
					$rawdata['FLIGHTMAPS']['FlightMapLandingProfile'] = $img;
				
				continue;
			}
			
			elseif($key == 'FLIGHTPLAN')
			{
				$rawdata['FLIGHTPLAN'] = (string) $value;
				continue;
			}
			elseif($key == 'FLIGHTCRITIQUE')
			{
				$value = trim((string)$value);
				$rawdata['FLIGHTCRITIQUE'] = $value;
				continue;
			}
			else
			{
				$key = trim($key);
				$value = (string) $value;
				$value = str_replace('¯Â', '', $value);
				
				$rawdata['FLIGHTDATA'][$key] = $value;
			}
		}
		
		
		/* Our data to send to the submit PIREP function */
		$data = array(
			'pilotid'=>$pilotid,
			'code'=>$code,
			'flightnum'=>$flightnum,
			'depicao'=>$depicao,
			'arricao'=>$arricao,
			'aircraft'=> $aircraft,
			'registration'=>(string) $xml->AircraftTailNumber,
			'flighttime'=> $flighttime,
			'landingrate'=> (string) $xml->ONVS,
			'submitdate'=>'NOW()',
			'comment'=> trim((string) $xml->COMMENT),
			'fuelused'=> (string) $xml->BlockFuel,
			'source'=>'fsfk',
			'load'=>$load,
			'log'=>$log,
			'rawdata'=>$rawdata,
		);
				
		//$this->log(print_r($rawdata, true), 'fsfk');
		
		$ret = ACARSData::FilePIREP($pilotid, $data);
		
		if(!$ret)
			echo PIREPData::$lasterror;
		else
			echo '<script type="text/javascript">window.location="'.url('/pireps/view/'.ACARSData::$pirepid).'";</script>';
	}
	
	/**
	 * Process ACARS messages here
	 * 
	 */
	public function acars()
	{
		if (!isset($_REQUEST['DATA1'])) die("0|Invalid Data");
		if (!isset($_REQUEST['DATA1'])) die("0|Invalid Data");
		
		// TEST, BEGINFLIGHT, PAUSEFLIGHT, ENDFLIGHT and MESSAGE
		$method = strtoupper($_REQUEST['DATA2']);
		
		if (!isset($_REQUEST['DATA3']))
			$value = '';
		else
			$value = $_REQUEST['DATA3'];
		
		if (!isset($_REQUEST['DATA4'])) 
			$message = '';
		else
			$message = $_REQUEST['DATA4'];
			
			
		$this->log("Method: {$method}", 'fsfk');
		
		$fields = array();
			
		# Go through each method now
		if($method == 'TEST')
		{
			$pilotid = $value;
			
			echo '1|30';
			return;
		}
		elseif($method == 'BEGINFLIGHT')
		{
            $flight_data = explode('|', $value);
	            
            if (count($flight_data) < 10) 
            {
                echo '0|Invalid login data';
                return;
            }
			
			preg_match('/^([A-Za-z]*)(\d*)/', $flight_data[0], $matches);
			$code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
			
			$coords = $this->get_coordinates($flight_data[6]);
			
			$route = explode('~', $flight_data[5]);
			$depicao = $route[0];
			$arricao = $route[count($route)-1];
			
			unset($route[0]);
			unset($route[count($route) - 1]);
			
			$route = implode(' ', $route);
			
			$flightinfo = SchedulesData::getProperFlightNum($flight_data[2]);
			$code = $flightinfo['code'];
			$flightnum = $flightinfo['flightnum'];
			
			$aircraft = $flight_data[3];
			$heading = $flight_data[12];
			$alt = $flight_data[7];
			$gs = 0;
			$dist_remain = $flight_data[16];
			$time_remain = 0;
			$deptime = time();
			$online = 0;
			
			$fields = array(
				'pilotid'=>$pilotid,
				'flightnum'=>$code.$flightnum,
				'aircraft'=>$aircraft,
				'lat'=>$coords['lat'],
				'lng'=>$coords['lng'],
				'heading'=>$heading,
				'route' => $route,
				'alt'=>$alt,
				'gs'=>$gs,
				'depicao'=>$depicao,
				'arricao'=>$arricao,
				'distremain'=>$dist_remain,
				'timeremaining'=>$time_remain,
				'phasedetail'=>'On the ground',
				'online'=>$online,
				'client'=>'fsfk',
			);
        }
        elseif($method == 'MESSAGE')
        {
			$pilotid = $value;			
			$flight_data = ACARSData::get_flight_by_pilot($pilotid);
			
			// Get the flight
			preg_match("/Flight ID: (.*)\n/", $message, $matches);
			$flightinfo = SchedulesData::getProperFlightNum($matches[1]);
			$code = $flightinfo['code'];
			$flightnum = $flightinfo['flightnum'];
			
			// Get the aircraft 
			preg_match("/.*Aircraft Reg: \.\.(.*)\n/", $message, $matches);
			$aircraft_data = OperationsData::GetAircraftByReg(trim($matches[1]));
			
			$aircraft = $aircraft_data->id;
			$depicao = $flight_data->depicao;
			$arricao = $flight_data->arricao;
			
			// Get coordinates from ACARS message
			$count = preg_match("/POS(.*)\n/", $message, $matches);
			if($count > 0)
			{
				$coords = $this->get_coordinates(trim($matches[1]));
			}
			else
			{
				$coords = array('lat' => $flight_data->lat, 'lng' => $flight_data->lng);
			}
			
			// Get our heading
			preg_match("/\/HDG.(.*)\n/", $message, $matches);
			$heading = $matches[1];
			
			// Get our altitude
			preg_match("/\/ALT.(.*)\n/", $message, $matches);
			$alt = $matches[1];
			
			// Get our speed
			preg_match("/\/IAS.(.*)\//", $message, $matches);
			$gs = $matches[1];
			
			$fields = array(
				'pilotid'=>$pilotid,
				'aircraft' => $aircraft,
				'lat'=>$coords['lat'],
				'lng'=>$coords['lng'],
				'heading'=>$heading,
				'alt'=>$alt,
				'gs'=>$gs,
				'phasedetail'=>'Enroute',
			);
		}
		elseif($method == 'UPDATEFLIGHTPLAN')
		{
			$flight_id = $value;
			$flight_data = explode("|", $message);
			
			echo '1|';
			return;
		}
		
		$depapt = OperationsData::GetAirportInfo($depicao);
		$dist_remain = SchedulesData::distanceBetweenPoints($coords->lat, $coords->lng, $depapt->lat, $depapt->lng);
		
		# Estimate the time remaining
		if($gs != 0)
			$time_remain = $dist_remain / $gs;
		else
			$time_remain = '0';
			
		$fields['distremain'] = $dist_remain;
		$fields['timeremaining'] = $time_remain;
		
		if($deptime != '')
		{
			$fields['deptime'] = $deptime;
		}
		
		if($arrtime != '')
		{
			$fields['arrtime'] = $arrtime;
		}
		
		Debug::log(print_r($fields, true), 'fsfk');
		ACARSData::updateFlightData($pilotid, $fields);
		$id = DB::$insert_id;
        
        if($method == 'BEGINFLIGHT')
		{
			echo '1|'.$pilotid;
			return;
		}
		
        echo '1|';
	}
	
	/**
	 *  Process information for FSFK live watch
	 *
	 */
	public function livewatch()
	{
		
	}
	
	
	/**
	 * Give the user the vaconfig file to use with FSFK
	 *
	 */
	public function vaconfig_template()
	{
		$this->write_template('fsfk_vaconfig', 'VA-Template.txt');
	}
	
	public function liveacars_template()
	{
		$this->write_template('fsfk_liveacars_config', 'LiveACARS.txt');
	}
	
	public function pirep_template()
	{
		$this->write_template('fsfk_pirep_config', 'Web.txt');
	}
	
	public function email_template()
	{
		
	}
	
	public function airtv_template()
	{
		
	}
	
	protected function write_template($name, $save_as)
	{
		$this->set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
		$this->set('userinfo', Auth::$userinfo);
		
		$acars_config = Template::GetTemplate($name, true);
		$acars_config = str_replace("\n", "\r\n", $acars_config);
		
		Util::downloadFile($acars_config, $save_as);
		
		return;
		# Set the headers so the browser things a file is being sent
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="'.$save_as.'"');
		header('Content-Length: ' . strlen($acars_config));
		
		echo $acars_config;
	}
	
	protected function get_coordinates($line)
	{
		/* Get the lat/long */
		preg_match('/^([A-Za-z])(\d*).(\d*.\d*).([A-Za-z])(\d*).(\d*.\d*)/', $line, $coords);
		
		$lat_dir = $coords[1];
		$lat_deg = $coords[2];
		$lat_min = $coords[3];
		
		$lng_dir = $coords[4];
		$lng_deg = $coords[5];
		$lng_min = $coords[6];
		
		$lat_deg = ($lat_deg*1.0) + ($lat_min/60.0);
		$lng_deg = ($lng_deg*1.0) + ($lng_min/60.0);
		
		if(strtolower($lat_dir) == 's')
			$lat_deg = '-'.$lat_deg;
			
		if(strtolower($lng_dir) == 'w')
			$lng_deg = $lng_deg*-1;
		
		/* Return container */
		$coords = array();
		$coords['lat'] = $lat_deg;
		$coords['lng'] = $lng_deg;
		
		return $coords;
	}
}