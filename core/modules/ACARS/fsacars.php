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
 

##################################

function writedebug($msg)
{
	$debug = true;
	
	if(!$debug)
		return;
		
	$fp = fopen('/home/nssliven/public_html/phpvms/test/core/modules/ACARS/log.txt', 'a+');
	$msg .= '
';
	fwrite($fp, $msg, strlen($msg));
	
	fclose($fp);
}

##################################
	
# Our flight phase constants
#	Don't change the order, the key is the # given by FSACARS
$phase_short = array('null', 'Boarding', 'Departing', 'Cruise', 'Arrived');

$phase_detail = array('FSACARS Closed', 'Boarding', 'Taxiing', 'Takeoff', 'Climbing',
				 	  'Cruise', 'Landing Shortly', 'Landed', 'Taxiiing to gate', 'Arrived');

$flightcargo = array('Pax', 'Cargo');

##################################

switch($_GET['action'])
{
	# ACARS status change message
	
	case 'acars':
	
		writedebug("ACARS UPDATE");

		$pilotid = $_GET['pilotnumber'];
		
		$fields = array('pilotid'=>$_GET['pilotnumber'],
						'messagelog'=>str_ireplace('Message content: £', '', $_GET['mcontent']).'\n');
		
		ob_start();
		ACARSData::UpdateFlightData($fields);
		echo DB::err();
		$cont = ob_get_clean();
		
		ob_end_clean();
		
		writedebug($cont);
		
		break;
	
	# Position Update
	case 'status':
	
		writedebug("STATUS UPDATE");
		
		$fields = array('pilotid'=>$_GET['pnumber'],
					'flightnum'=>$_GET['IATA'],
					'pilotname'=>'',
					'aircraft'=>$_GET[''],
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
					'phasedetail'=>$phase_detail[$_GET['detailph']],
					'online'=>$_GET['Online'],
					'client'=>'FSACARS');

		ob_start();
		
			ACARSData::UpdateFlightData($fields);
			$cont = ob_get_clean();
			
		ob_end_clean();
		
		writedebug($cont);
		
		break;
		
	# File the PIREP	
	case 'pirep':

		writedebug("PIREP FILE");
			
		// see if they are a valid pilot:
		preg_match('/^([A-Za-z]{2,3})(\d*)', $_GET['pilotnumber'], $matches);
		$pilotid = $matches[2];
		
		/*if(!($pilot = PilotData::GetPilotData($pilotid)))
		{
			return;
		}*/
		
		// match up the flight info
		preg_match('/^([A-Za-z]{2,3})(\d*)', $_GET['callsign'], $matches);
		$code = $matches[1];
		$flightnum = $matches[2];
		$depicao = $_GET['depart'];
		$arricao = $_GET['arrival'];
		$aircraft = $_GET['equipment'];
		$flighttime = $_GET['duration'];
		$comment = '';
		$log = $_GET['log'];
		
		$data = array('pilotid'=>$pilotid,
					'code'=>$code,
					'flightnum'=>$flightnum,
					'leg'=>$leg,
					'depicao'=>$depicao,
					'arricao'=>$arricao,
					'aircraft'=>$aircraft,
					'flighttime'=>$flighttime,
					'submitdate'=>'NOW()',
					'comment'=>$comment,
					'log'=>$log);
		
		$res = PIREPData::FileReport($data);
		if(!$res)
			writedebug(DB::err());
			
		echo 'OK';
		break;
}

?>