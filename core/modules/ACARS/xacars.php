<?php

/**
 * phpVMS ACARS integration
 *
 * Interface for use with XACARS
 * http://www.xacars.net/
 * 
 * 
 * This file goes as this:
 *	The URL given is:
 *		<site>/index.php/acars/xacars/<action>
 * 
 * SDK Docs: http://www.xacars.net/index.php?Client-Server-Protocol
 */
 
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('display_errors', 'on');
	
Debug::log($_SERVER['QUERY_STRING'], 'xacars');
Debug::log($_SERVER['REQUEST_URI'], 'xacars');
Debug::log(serialize($_REQUEST), 'xacars');

class Coords {
	public $lat;
	public $lng;
}

function get_coordinates($line)
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
	$coords = new Coords();
	$coords->lat = $lat_deg;
	$coords->lng = $lng_deg;
	
	return $coords;
}


switch($acars_action)
{
	
	/* Request data about a flight */
	case 'data':
	
		$flight = $_REQUEST['DATA2'];
		Debug::log('FLIGHT PLAN REQUEST', 'xacars');
		
		# They requested latest bid
		if(strtolower($flight) == 'bid')
		{
			preg_match('/^([A-Za-z]*)(\d*)/', $_REQUEST['DATA4'], $matches);
			$code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
		
			$route = SchedulesData::GetLatestBid($pilotid);
			
			if(!$route)
			{
				echo '0|No bids found!';
				return;
			}
		}
		else
		{
			
			if(is_numeric($flight))
			{
				echo '0|No airline code entered!';
				return;
			}
			
			$flightinfo = SchedulesData::getProperFlightNum($flight);
			$code = $flightinfo['code'];
			$flight_num = $flightinfo['flightnum'];
			
			$route = SchedulesData::GetScheduleByFlight($code, $flight_num);
			
			Debug::log(print_r($route, true), 'xacars');
			
			if(!$route)
			{
				echo '0|Flight not found, make sure you include the flight code!';
				return;
			}
		}
		
		/* Ok to proceed */
		if($route->flighttype=='H')
		{
			$maxpax = $route->maxpax;
		}
		else
		{
			if($route->flighttype=='C')
			{
				$maxcargo = FinanceData::GetLoadCount($route->aircraftid, 'C');
			}
			else
			{
				$maxpax = FinanceData::GetLoadCount($route->aircraftid, 'P');
			}
		}
		
		echo
"1|flightplan
$route->depicao
$route->arricao
$route->arricao
$route->route
$maxpax
$maxcargo
IFR
$route->registration
$route->flightlevel
";
		
		break;
				
	case 'acars':
	case 'xacars':
	
		# Pass success by default
		$outstring = 'Success';
			
		$_REQUEST['DATA2'] = strtoupper($_REQUEST['DATA2']);	
		if($_REQUEST['DATA2'] == 'TEST')
		{
			echo '1|OK';
			return;
		}
		elseif($_REQUEST['DATA2'] == 'ENDFLIGHT')
		{
			echo '1|OK';
			return;
		}
		elseif($_REQUEST['DATA2'] == 'PAUSEFLIGHT')
		{
			echo '1|OK';
			return;
		}
		elseif($_REQUEST['DATA2'] == 'BEGINFLIGHT')
		{
			/*	
			VMA001||VMW5421|N123K5||KORD~~KMIA|N51 28.3151 W0 26.8892|88||||59|328|00000|14|IFR|0||
			*/
			Debug::log('BEGINFLIGHT', 'xacars');
			$data = explode('|', $_REQUEST['DATA3']);
			
			/* Get the pilot info */
			preg_match('/^([A-Za-z]*)(\d*)/', $data[0], $matches);
			$code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
			
			/* Get Coordinates */
			$coords = get_coordinates($data[6]);
						
			/* Get route */
			$route = explode('~', $data[5]);
			$depicao = $route[0];
			$arricao = $route[count($route)-1];
			
			$flightnum = $data[2];
			$aircraft = $data[3];
			$heading = $data[12];
			$alt = $data[7];
			$deptime = time();
			
			$outstring = $pilotid;			
		}
		elseif($_REQUEST['DATA2'] == 'MESSAGE')
		{
		
			$data = $_REQUEST['DATA4'];
			$pilotid = $_REQUEST['DATA3'];
			
			/* Get the flight information, from ACARS, need to
				pull the latest flight data via the flight number
				since acars messages don't transmit the pilot ID */
			
			preg_match("/Flight ID:.(.*)\n/", $data, $matches);
			
			$flight_data = ACARSData::get_flight_by_pilot($pilotid);
			
			Debug::log('Flight data:', 'xacars');
			Debug::log(print_r($_REQUEST, true), 'xacars');
			
			Debug::log('PilotID: '.$pilotid, 'xacars');
			
			$flightnum = $flight_data->flightnum;
			$aircraft = $flight_data->aircraft;
			$depicao = $flight_data->depicao;
			$arricao = $flight_data->arricao;
					
			// Get coordinates from ACARS message
			preg_match("/POS(.*)\n/", $data, $matches);
			$coords = get_coordinates(trim($matches[1]));
			
			// Get our heading
			preg_match("/\/HDG.(.*)\n/", $data, $matches);
			$heading = $matches[1];
			
			// Get our altitude
			preg_match("/\/ALT.(.*)\n/", $data, $matches);
			$alt = $matches[1];
			
			// Get our  speed
			preg_match("/\/IAS.(.*)\//", $data, $matches);
			$gs = $matches[1];
			
			// Get the OUT time
			preg_match("/OUT.(.*) \/ZFW/", $data, $matches);
			$deptime = $matches[1];
		}
		else
		{
			Debug::log('else', 'xacars');
			return;
		}
		
		# Get the distance remaining
		$depapt = OperationsData::GetAirportInfo($depicao);
		$dist_remain = SchedulesData::distanceBetweenPoints($coords->lat, $coords->lng, $depapt->lat, $depapt->lng);
		
		# Estimate the time remaining
		if($gs > 0)
		{
			$time_remain = $dist_remain / $gs;
		}
		else
		{
			$time_remain = '00:00';
		}

		ob_start();
		$fields = array(
			'pilotid'=>$pilotid,
			'flightnum'=>$flightnum,
			'pilotname'=>'',
			'aircraft'=>$aircraft,
			'lat'=>$coords->lat,
			'lng'=>$coords->lng,
			'heading'=>$heading,
			'alt'=>$alt,
			'gs'=>$gs,
			'depicao'=>$depicao,
			'arricao'=>$arricao,
			'deptime'=>$deptime,
			'arrtime'=>'',
			'distremain'=>$dist_remain,
			'timeremaining'=>$time_remain,
			'phasedetail'=>'Enroute',
			'online'=>$_GET['Online'],
			'client'=>'xacars',
		);
		
		Debug::log(print_r($fields, true), 'xacars');
		
		ACARSData::UpdateFlightData($fields);
		
		echo '1|'.$outstring;
		break;
		
	case 'pirep':
		
		$data = explode('~', $_REQUEST['DATA2']);
				
		/*preg_match('/^([A-Za-z]*)(\d*)/', $data[2], $matches);
		$code = $matches[1];
		$flightnum = $matches[2];*/
		
		$flightinfo = SchedulesData::getProperFlightNum($data[2]);
		$code = $flightinfo['code'];
		$flightnum = $flightinfo['flightnum'];
		
		if(!is_numeric($data[0]))
		{
			# see if they are a valid pilot:
			preg_match('/^([A-Za-z]*)(\d*)/', $data[0], $matches);
			$pilot_code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
		}
		else
		{
			$pilotid = $data[0];
		}
		
		
		# Make sure airports exist:
		#  If not, add them.
		$depicao = $data[6];
		$arricao = $data[7];
		
		if(!OperationsData::GetAirportInfo($depicao))
		{
			OperationsData::RetrieveAirportInfo($depicao);
		}
		
		if(!OperationsData::GetAirportInfo($arricao))
		{
			OperationsData::RetrieveAirportInfo($arricao);
		}
		
		# Get aircraft information
		$reg = trim($data[3]);
		$ac = OperationsData::GetAircraftByReg($reg);
		
		# Load info
		/* If no passengers set, then set it to the cargo */
		$load = $data[14];
		if($load == '' || $load == 0)
			$load = $data[15];
		
		# Convert the time to xx.xx 
		$flighttime = floatval(str_replace(':', '.', $data[11])) * 1.00;
		
		/* Fuel conversion - XAcars only reports in lbs */
		$fuelused = $data[12];
		if(Config::Get('LiquidUnit') == '0')
		{
			# Convert to KGs, divide by density since d = mass * volume
			$fuelused = ($fuelused * .45359237) / .8075;
		}
		# Convert lbs to gallons
		elseif(Config::Get('LiquidUnit') == '1')
		{
			$fuelused = $fuelused * 6.84;
		}
		# Convert lbs to kgs
		elseif(Config::Get('LiquidUnit') == '2')
		{
			$fuelused = $fuelused * .45359237;
		}
		
		$data = array('pilotid'=>$pilotid,
				'code'=>$code,
				'flightnum'=>$flightnum,
				'depicao'=>$depicao,
				'arricao'=>$arricao,
				'aircraft'=>$ac->id,
				'flighttime'=>$flighttime,
				'submitdate'=>'NOW()',
				'comment'=>$comment,
				'fuelused'=>$fuelused,
				'source'=>'xacars',
				'load'=>$load,
				'log'=> $_GET['log']);
				
		Debug::log(print_r($data, true), 'xacars');
		
		$ret = ACARSData::FilePIREP($data[0], $data);
				
		echo '1|Success';
		break;
}