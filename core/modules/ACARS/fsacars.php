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
 
writedebug($_SERVER['QUERY_STRING']);

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

switch($_GET['action'])
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
			$pilotid = $matches[2];
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
		$route = SchedulesData::GetLatestBid($_GET['pilot']);
		//print_r($route);
		$date=date('Ymd');
		
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


$route->aircraft

$date
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
		
		$fields = array('pilotid'=>$_GET['pnumber'],
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
		
		writedebug($fields);
		
		ACARSData::UpdateFlightData($fields);
		$cont = ob_get_clean();
			
		ob_end_clean();
		
		writedebug($cont);
		
		break;
	
	#
	# File the PIREP
	#
	case 'pirep':
	
		ini_set('display_errors', 'off');

		writedebug("PIREP FILE");
		writedebug(print_r($_GET, true));
			
		if(is_numeric($_GET['pilot']))
		{
			$pilotid = $_GET['pilot'];
		}
		else
		{
			# see if they are a valid pilot:
			preg_match('/^([A-Za-z]*)(\d*)/', $_GET['pilot'], $matches);
			$code = $matches[1];
			$pilotid = $matches[2];
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
		preg_match('/^([A-Za-z]*)(\d*)/', $flightnum, $matches);
		$code = $matches[1];
		$flightnum = $matches[2];
		
		# Get the passenger count:
		# Find where flight IATA is
		$pos = find_in_fsacars_log('PAX', $log);
		$load = str_replace('PAX:', '', $log[$pos]);
		
		# Get our aircraft
		$reg = trim($_GET['reg']);
		$ac = OperationsData::GetAircraftByReg($reg);
		
		# Get the fuel used
		/*$pos = find_in_fsacars_log('Spent Fuel', $log);
		preg_match('/^.*Spent Fuel: (\d*)/', $log[$pos], $matches);
		$fuelused = $_GET['fuel'];*/
		
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
		$flighttime = str_replace(':', '.', $_GET['duration']);
		
		$data = array('pilotid'=>$pilotid,
						'code'=>$code,
						'flightnum'=>$flightnum,
						'leg'=>$leg,
						'depicao'=>$_GET['origin'],
						'arricao'=>$_GET['dest'],
						'aircraft'=>$ac->id,
						'flighttime'=>$flighttime,
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