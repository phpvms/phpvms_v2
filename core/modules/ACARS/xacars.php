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
 

error_reporting(0);
writedebug($_SERVER['QUERY_STRING']);
writedebug($_SERVER['REQUEST_URI']);
writedebug(print_r($_REQUEST, true));

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

switch($_GET['action'])
{
	
	/* Request data about a flight */
	case 'data':
	
		$flight = $_REQUEST['DATA2'];
		writedebug('FLIGHT PLAN REQUEST');
		
		# They requested latest bid
		if(strtolower($_REQUEST['DATA2']) == 'bid')
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
			# split the flight request:
			preg_match('/^([A-Za-z]*)(\d*)/', $flight, $matches);
			$code = $matches[1];
			$flight_num = $matches[2];
			
			$route = SchedulesData::GetScheduleByFlight($code, $flight_num);
			
			writedebug(print_r($route, true));
			
			if(!$route)
			{
				echo '0|Flight not found!';
				return;
			}
		}
		
		/* Ok to procede */
		
		
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
0
";
		
		break;
				
	case 'acars':
	
		if($_REQUEST['DATA2'] == 'TEST')
		{
			
		}
		
		if(strtoupper($_REQUEST['DATA2']) == 'BEGINFLIGHT')
		{
			/*	
			VMA001||VMW5421|N123K5||KORD~~KMIA|N51 28.3151 W0 26.8892|88||||59|328|00000|14|IFR|0||
			*/
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
			
		}
		elseif(strtoupper($_REQUEST['DATA2']) == 'MESSAGE')
		{
			$data = $_REQUEST['DATA4'];
			
			/* Get the flight information, from ACARS, need to
				pull the latest flight data via the flight number
				since acars messages don't transmit the pilot ID */
			preg_match("/Flight ID:.([A-Za-z]*)([0-9]*)\n/", $data, $matches);
			$flight_data = ACARSData::get_flight($matches[1], $matches[2]);
					
			$pilotid = $flight_data->pilotid;
			$flightnum = $flight_data->flightnum;
			$aircraft = $flight_data->aircraft;
			$depicao = $flight_data->depicao;
			$arricao = $flight_data->arricao;
					
			// Get coordinates from ACARS message
			preg_match("/\/POS.(.*)\n/", $data, $matches);
			$coords = get_coordinates($matches[1]);
			
			// Get our heading
			preg_match("/\/HDG.(.*)\n/", $data, $matches);
			$heading = $matches[1];
			
			// Get our altitude
			preg_match("/\/ALT.(.*)\n/", $data, $matches);
			$alt = $matches[1];
			
			// Get our  speed
			preg_match("/\/IAS.(.*)\n/", $data, $matches);
			$gs = $matches[1];
			
			// Get the OUT time
			preg_match("/OUT.(.*) \/ZFW/", $data, $matches);
			$deptime = $matches[1];
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
			'depapt'=>'',
			'arricao'=>$arricao,
			'arrapt'=>'',
			'deptime'=>$deptime,
			'arrtime'=>'',
			'distremain'=>$_GET['disdestapt'],
			'timeremaining'=>$_GET['timedestapt'],
			'phasedetail'=>$phase_detail[$_GET['detailph']],
			'online'=>$_GET['Online'],
			'client'=>'xacars'
		);
		
		writedebug($fields);
		
		ACARSData::UpdateFlightData($fields);
		
		echo '1|Success';
		break;
		
	case 'pirep':
		
		$data = explode('~', $_REQUEST['DATA2']);
				
		preg_match('/^([A-Za-z]*)(\d*)/', $data[2], $matches);
		$code = $matches[1];
		$flightnum = $matches[2];
		
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
		$load = $data[14];
		
		# Convert the time to xx.xx 
		$flighttime = floatval(str_replace(':', '.', $data[11])) * 1.00;
		
		$data = array('pilotid'=>$data[0],
				'code'=>$code,
				'flightnum'=>$flightnum,
				'depicao'=>$depicao,
				'arricao'=>$arricao,
				'aircraft'=>$ac->id,
				'flighttime'=>$flighttime,
				'submitdate'=>'NOW()',
				'comment'=>$comment,
				'fuelused'=>$data[12],
				'source'=>'xacars',
				'load'=>$load,
				'log'=> $_GET['log']);
				
		echo '1|Success';
		break;
}