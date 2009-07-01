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

switch($_GET['action'])
{
	
	/* Request data about a flight */
	case 'flightdata':
	
		$flight = $_REQUEST['DATA2'];
		writedebug('FLIGHT PLAN REQUEST');
		
		# see if they are a valid pilot:
		preg_match('/^([A-Za-z]*)(\d*)/', $flight, $matches);
		$code = $matches[1];
		$flight_num = $matches[2];
			
		$route = SchedulesData::GetScheduleByFlight($code, $flight_num);
		
		if(!$route)
		{
			echo "0|error message\n";
			return;
		}
		
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
	
	
		break;
	
}