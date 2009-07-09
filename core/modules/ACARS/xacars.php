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
		$lng_deg = '-'.$lng_deg;
	
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
		
	case 'pirep':
	
	
		break;
		
	case 'acars':
	
		if($_REQUEST['DATA2'] == 'TEST')
		{
			
		}
		
		if($_REQUEST['DATA2'] == 'BEGINFLIGHT')
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
			
			$fields = array(
				'pilotid'=>$pilotid,
				'flightnum'=>$data[2],
				'pilotname'=>'',
				'aircraft'=>$data[3],
				'lat'=>$coords->lat,
				'lng'=>$coords->lng,
				'heading'=>$data[12],
				'alt'=>$data[7],
				'gs'=>$_GET['GS'],
				'depicao'=>$depicao,
				'depapt'=>'',
				'arricao'=>$arricao,
				'arrapt'=>'',
				'deptime'=>'',
				'arrtime'=>'',
				'distremain'=>$_GET['disdestapt'],
				'timeremaining'=>$_GET['timedestapt'],
				'phasedetail'=>$phase_detail[$_GET['detailph']],
				'online'=>$_GET['Online'],
				'client'=>'xacars'
			);
		}

		ob_start();
		writedebug($fields);
		
		ACARSData::UpdateFlightData($fields);
		
		echo '1|Success';
		break;
	
}