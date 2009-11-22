<?php

/**
 * phpVMS ACARS integration
 *
 * Interface for use with FSACARS
 * http://www.satavirtual.org/fsacars/
 * 
 * 
 * This file goes as this:
 *	The URL given is:
 *		<site>/index.php/acars/fsacars/<action>
 * 
 *  The action is set in the fsacas INI file:
 * 
 *		acars
 *		flightplan
 *		status
 *		pirep
 * 
 *  Pretty self-explanitory. I just check for the action ($_GET[action]),
 *	then follow the SDK docs to parse the message.
 * 
 *  There is a API for the ACARS, the ACARSData class.
 * 
 *  Anything inside the output buffering regions is thrown out
 *	unless debug = true in the function below
 */

error_reporting(0);
ini_set('display_errors', 'off');

Debug::log(serialize($_SERVER['QUERY_STRING']), 'acars');

##################################
	
# Our flight phase constants
#	Don't change the order, the key is the # given by FSACARS

$phase_short = array('null', 'Boarding', 'Departing', 'Cruise', 'Arrived');

$phase_detail = array('FSACARS Closed', 'Boarding', 'Taxiing', 'Takeoff', 'Climbing',
				 	  'Cruise', 'Landing Shortly', 'Landed', 'Taxiiing to gate', 'Arrived');

$flightcargo = array('Pax', 'Cargo');

##################################


function find_in_fsacars_log($txt, $log)
{
	$total = count($log);
	for($i=0;$i<$total; $i++)
	{
		if(strstr($log[$i], $txt) === false)
		{
			continue;
		}
		else
		{
			return $i;
		}
	}
}


$_GET = unserialize('a:18:{s:5:"pilot";s:10:"VMSVMS0001";s:4:"date";s:10:"2009/11/22";s:4:"time";s:8:"02:01:00";s:8:"callsign";s:0:"";s:3:"reg";s:5:"N4567";s:6:"origin";s:4:"KJFK";s:4:"dest";s:4:"KLGA";s:3:"alt";s:0:"";s:9:"equipment";s:0:"";s:4:"fuel";s:2:"00";s:8:"duration";s:5:"00:06";s:8:"distance";s:2:"12";s:7:"version";s:4:"4015";s:4:"more";s:1:"0";s:3:"log";s:889:"[2009/11/22 02:01:00]*Flight IATA:VMS4567*Pilot Number:VMS0001*Company ICAO:VMS*Aircraft Registration:N4567*Departing Airport: KJFK*Destination Airport: KLGA*Online: No*Route:DIRECT*Flight Level:040*02:01  Zero fuel Weight: 10118 Lbs, Fuel Weight: 3602 Lbs*02:02  Parking Brakes off*02:02  Com1 Freq=128.30*02:03  VR= 142 Knots*02:03  V2= 148 Knots*02:03  Take-off*02:03  Take off Weight: 13720 Lbs*02:03  Wind: 000? @ 000 Knots Heading: 035?*02:03  POS N40? 38? 03?? W073? 46? 25?? *02:03  N11 99 N12 99*02:03  TOC*02:03  Fuel Weight: 3603 Lb*02:05  Flaps:1 at 209 Knots*02:05  Flaps:2 at 209 Knots*02:07  TouchDown:Rate -378 ft/min Speed: 143 Knots*02:08  Land*02:08  Wind:000?@000 Knots*02:08  Heading: 174?*02:08  Flight Duration: 00:05 *02:08  Landing Weight: 13720 Lbs*02:08  POS N40? 46? 43?? W073? 52? 23?? *02:08  Parking brakes on*02:08  Block to Block Duration: 00:06 *02:08  Fi";s:6:"module";s:5:"acars";s:6:"action";s:7:"fsacars";s:4:"page";s:7:"fsacars";}');

switch($acars_action)
{
	#
	# ACARS status change message
	#	Code is here but currently not implemented
	#	or tested
	#
	
	case 'acars':
		
		writedebug('ACARS UPDATE');
		writedebug(print_r($_GET, true));

		$pilotid = $_GET['pilotnumber'];
		
		if(!is_numeric($pilotid))
		{
			preg_match('/^([A-Za-z]*)(\d*)/', $pilotid, $matches);
			$code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
		}
		
		$fields = array('pilotid'=>$_GET['pilotnumber'],
						'messagelog'=>str_ireplace('Message content: £', '', $_GET['mcontent']).'\n');
		
		ob_start();
		ACARSData::UpdateFlightData($fields);
		
		$cont = ob_get_clean();
		
		ob_end_clean();
		
		writedebug($cont);
		
		break;
		
	case 'flightplans':
	case 'schedules':
	
		writedebug('FLIGHT PLAN REQUEST');
		
		if(!is_numeric($_GET['pilot']))
		{
			# see if they are a valid pilot:
			preg_match('/^([A-Za-z]*)(\d*)/', $_GET['pilot'], $matches);
			$code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
		}
		
		$route = SchedulesData::GetLatestBid($pilotid);
		$date=date('Y:m:d');
		
		# Get load counts
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
		
		//$starttime = 
		
echo "OK
$route->depicao
$route->arricao
$route->flightlevel

$route->aircraft


$route->code$route->flightnum
$route->registration
$route->code
$route->route


$maxpax
$maxcargo";	
		break;
	
	#
	# Position Update
	#
	case 'status':
	
		writedebug('STATUS UPDATE');
		writedebug(print_r($_GET, true));
		
		if($_GET['detailph']=='')
		{
			# Vary our detail phase based on the general phase if none is supplied
			#	Depending on the FSACARs version
			
			if($_GET['Ph'] == 1)
				$_GET['detailph'] = 1;
			elseif($_GET['Ph'] == 2)
				$_GET['detailph'] = 3;
			elseif($_GET['Ph'] == 3)
				$_GET['detailph'] = 5;
			elseif($_GET['Ph'] == 4)
				$_GET['detailph'] = 9;
			else
				$_GET['detailph'] = 1;
		}
		
		if(!is_numeric($_GET['pnumber']))
		{
			# see if they are a valid pilot:
			preg_match('/^([A-Za-z]*)(\d*)/', $_GET['pnumber'], $matches);
			$code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
		}
		
		$fields = array('pilotid'=>$pilotid,
						'flightnum'=>$_GET['IATA'],
						'pilotname'=>'',
						'aircraft'=>$_GET['Regist'],
						'lat'=>$_GET['lat'],
						'lng'=>$_GET['long'],
						'heading'=>'',
						'alt'=>$_GET['Alt'],
						'gs'=>$_GET['GS'],
						'depicao'=>$_GET['depaptICAO'],
						'depapt'=>$_GET['depapt'],
						'arricao'=>$_GET['destaptICAO'],
						'arrapt'=>$_GET['destapt'],
						'deptime'=>'',
						'arrtime'=>'',
						'distremain'=>$_GET['disdestapt'],
						'timeremaining'=>$_GET['timedestapt'],
						'phasedetail'=>$phase_detail[$_GET['detailph']],
						'online'=>$_GET['Online'],
						'client'=>'FSACARS');

		ob_start();
		
		Debug::log(print_r($fields, true), 'acars');
		
		ACARSData::UpdateFlightData($fields);
		$cont = ob_get_clean();
			
		ob_end_clean();
		
		writedebug($cont);
		
		break;
	
	#
	# File the PIREP
	#
	case 'pirep':
	
		Debug::log('PIREP FILE', 'acars');
		Debug::log(serialize($_GET), 'acars');
			
		if(is_numeric($_GET['pilot']))
		{
			$pilotid = $_GET['pilot'];
		}
		else
		{
			# see if they are a valid pilot:
			preg_match('/^([A-Za-z]*)(\d*)/', $_GET['pilot'], $matches);
			$code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
		}

		if(!($pilot = PilotData::GetPilotData($pilotid)))
		{
			writedebug('INVALID PID');
			return;
		}
				
		#
		# Check if anything was in the log
		#	If not, then it probably wasn't a multi-chunk, so
		#	 just pull it straight from the query string
		#	Otherwise, pull the full-text from the session
		#
		
		if($_GET['more'] == '1')
		{
			#
			# We have more coming to the log
			#
			$report = PIREPData::GetLastReports($pilotid, 1);
			PIREPData::AppendToLog($report->pirepid, $_GET['log']);	
						
			echo 'OK';
			return;
		}
		
		# Full PIREP, run with it
		preg_match('/^([A-Za-z]*)(\d*)/', $_GET['pilot'], $matches);
		$code = $matches[1];
		
		$log = explode('*', $_GET['log']);
		
		# Find where flight IATA is
		# And extract the code and flight number
		$pos = find_in_fsacars_log('Flight IATA', $log);
		$flightnum = str_replace('Flight IATA:', '', $log[$pos]);
		//preg_match('/^([A-Za-z]*)(\d*)/', $flightnum, $matches);
		
		$flightinfo = SchedulesData::getProperFlightNum($flightnum);
		$code = $flightinfo['code'];
		$flightnum = $flightinfo['flightnum'];
		
		# Get the passenger count:
		# Find where flight IATA is
		$pos = find_in_fsacars_log('PAX', $log);
		$load = str_replace('PAX:', '', $log[$pos]);
		
		$pos = find_in_fsacars_log('TouchDown:Rate', $log);
		$landingrate = str_replace('TouchDown:Rate', '', $log[$pos]);
		
		$count = preg_match('/([0-9]*:[0-9]*).*([-+]\d*).*/i', $landingrate, $matches);
		if($count > 0)
		{
			$landingrate = $matches[2];
		}
		else
		{
			$landingrate = 0;
		}
		
		//echo "$landingrate,  rate: $rate";
		
		# Get our aircraft
		$reg = trim($_GET['reg']);
		$ac = OperationsData::GetAircraftByReg($reg);
				
		# Do some cleanup
		$_GET['origin'] = DB::escape($_GET['origin']);
		$_GET['dest'] = DB::escape($_GET['dest']);
		
		# Get schedule info, using minimal information
		#	Check if they forgot the flight code
		if($code == '')
		{
			# Find a flight using just the flight code
			$sched = SchedulesData::FindFlight($matches[2]);
		
			# Can't do it. They completely fucked this up
			if(!$sched)
			{
				return;
			}
			
			$code = $sched->code;
			$flightnum = $sched->flightnum;
			$leg = $sched->leg;
			
			if($_GET['origin'] != $sched->depicao
				|| $_GET['dest'] != $sched->arricao)
			{
				$comment = 'phpVMS Message: Arrival or Departure does not match schedule';
			}
		}
			
		
		# Make sure airports exist:
		#  If not, add them.
		if(!OperationsData::GetAirportInfo($_GET['origin']))
		{
			OperationsData::RetrieveAirportInfo($_GET['origin']);
		}
		
		if(!OperationsData::GetAirportInfo($_GET['dest']))
		{
			OperationsData::RetrieveAirportInfo($_GET['dest']);
		}
		
		# Convert the time to xx.xx 
		$flighttime = number_format(floatval(str_replace(':', '.', $_GET['duration'])), 2);
		
		$data = array('pilotid'=>$pilotid,
						'code'=>$code,
						'flightnum'=>$flightnum,
						'depicao'=>$_GET['origin'],
						'arricao'=>$_GET['dest'],
						'aircraft'=>$ac->id,
						'flighttime'=>$flighttime,
						'landingrate'=>$landingrate,
						'submitdate'=>'NOW()',
						'comment'=>$comment,
						'fuelused'=>$_GET['fuel'],
						'source'=>'fsacars',
						'load'=>$load,
						'log'=> $_GET['log']);
			
		writedebug($data);
		$ret = ACARSData::FilePIREP($pilotid, $data);		
		if(!$res)
			writedebug(DB::error());
			
		echo 'OK';
		break;
}