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
 
/*  FSPassengers, Copyright © Daniel Polli
	http://www.fspassengers.com
 */

writedebug("FSPAX DEBUG");
//writedebug($_SERVER['QUERY_STRING']);
writedebug(print_r($_POST, true));
writedebug(serialize($_POST));

# Check for connection
if($_POST['FsPAskConnexion'] == 'yes')
{
	# Validate pilot:
	$_POST['UserName'] = DB::escape($_POST['UserName']);
	
	# Entered as ###
	if(is_numeric($_POST['UserName']))
	{
		$pilotid = intval(intval(trim( $_POST['UserName']))) - Config::Get('PILOTID_OFFSET');
	}
	else
	{
		# Check if they entered as XXX###
		if(preg_match('/^([A-Za-z]*)(.*)(\d*)/', $_POST['UserName'], $matches)>0)
		{
			$pilotid = intval(intval(trim($matches[2]))) - Config::Get('PILOTID_OFFSET');
		}
		else
		{
			# Invalid Pilot
			echo '#Answer# Error - Invalid pilot ID format;';
			return;
		}
	}
	
	$pilotdata = PilotData::GetPilotData($pilotid);
	if(!$pilotdata)
	{
		echo '#Answer# Error - Username don\'t exist or wrong password;';
		return;
	}
	
	# Give it what it wants
	# Derive the config from the main config settings
	echo "#Answer# Ok - connected;";
	echo 'Weight='.Config::Get('WeightUnit').' Dist='.Config::Get('DistanceUnit').' Speed='.Config::Get('SpeedUnit').' Alt='.Config::Get('AltUnit').' Liqu='.Config::Get('LiquidUnit');
	echo '#welcome#'.Config::Get('WelcomeMessage').'#endwelcome#';
}


if($_POST['FsPAskToRegister'] == 'yes')
{
	$comment = '';
	
	# Get the pilot id:
	
	if(is_numeric($_POST['UserName']))
	{
		$pilotid = intval(intval(trim( $_POST['UserName']))) - Config::Get('PILOTID_OFFSET');
	}
	else
	{
		if(preg_match('/^([A-Za-z]*)(\d*)/', $_POST['UserName'], $matches) == 0)
		{
			echo "#Answer# Error: Pilot doesn't exist ;";
			return;
		}
		
		$pilotid = intval(intval(trim($matches[2]))) - Config::Get('PILOTID_OFFSET');
	}
	
	# Get the flight ID
	$flightinfo = SchedulesData::getProperFlightNum($_POST['FlightId']);
	$code = $flightinfo['code'];
	$flightnum = $flightinfo['flightnum'];
	/*if(preg_match('/^([A-Za-z]*)(\d*)/', $_POST['FlightId'], $matches) == 0)
	{
		echo "#Answer# Error - Invalid flight ID;";
		return;
	}*/
	
				
	preg_match('/^([A-Za-z]*) - .*/', $_POST['DepartureIcaoName'], $aptinfo);
	$depicao = $aptinfo[1];
	
	# Make sure it exists
	if(!OperationsData::GetAirportInfo($depicao))
	{
		OperationsData::RetrieveAirportInfo($depicao);
	}

	preg_match('/^([A-Za-z]*) - .*/', $_POST['ArrivalIcaoName'], $aptinfo);
	$arricao = $aptinfo[1];
	
	# Make sure it exists
	if(!OperationsData::GetAirportInfo($arricao))
	{
		OperationsData::RetrieveAirportInfo($arricao);
	}
	
	//if($code == '')
	//{
		# Find a flight using just the flight code
		$sched = SchedulesData::FindFlight($flightnum);
		
		# Can't do it. They completely screwed this up
		if(!$sched)
		{
			echo "#Answer# Error - Invalid flight ID;";
			return;
		}
		
		$code = $sched->code;
		$flightnum = $sched->flightnum;
		$leg = $sched->leg;
		$aircraft = $sched->aircraft;
		/*$depicao = $sched->depicao;
		$arricao = $sched->arricao;*/
		
		if($depicao != $sched->depicao || $arricao != $sched->arricao)
		{
			$comment = 'phpVMS Message: Arrival or Departure does not match schedule. ';
		}
	//}

	# Get the time, don't care about seconds
	preg_match('/^(\d*):(\d*):(\d*)/', $_POST['TotalBlockTime'], $time);
	$flighttime = $time[1].'.'.$time[2];
	
	# Get the fuel used
	$fuelused = floatval($_POST['StartFuelQuantity']) - floatval($_POST['EndFuelQuantity']);
	
	# Form the log:
	$log = '';
	foreach($_POST as $name=>$value)
	{
		if($name == 'FsPAskToRegister' || $name == 'UserName' || $name == 'Password')
		{
			continue;
		}
		
		$log .= "$name=$value<br />".PHP_EOL;
	}
	
	$comment .= 'FSPassengers Flight. No aircraft entered';
	
	$data = array(	'pilotid'=>$pilotid,
					'code'=>$code,
					'flightnum'=>$flightnum,
					'leg'=>$leg,
					'depicao'=>$depicao,
					'arricao'=>$arricao,
					'aircraft'=>$aircraft,
					'flighttime'=>$flighttime,
					'landingrate'=>$_POST['TouchDownVertSpeedFt'],
					'submitdate'=>'NOW()',
					'load'=>$_POST['NbrPassengers'],
					'fuelused'=>$fuelused,
					'source'=>'fspax',
					'comment'=>$comment,
					'log'=> $log);
		
	writedebug($data);
		
	$ret = ACARSData::FilePIREP($pilotid, $data);
	if(!$ret)
	{
		echo "#Answer# Error - ".PIREPData::$lasterror;
		exit;
	}

	
	echo "#Answer# Ok - Saved;";
}