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

$val = SessionManager::GetData('test');
if($val == '')
{
	SessionManager::AddData('test', rand());
}

writedebug($val);
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
	#
	# ACARS status change message
	#	Code is here but currently not implemented
	#	or tested
	#
	
	case 'acars':
	
		writedebug('ACARS UPDATE');
		writedebug(print_r($_GET, true));

		$pilotid = $_GET['pilotnumber'];
		
		$fields = array('pilotid'=>$_GET['pilotnumber'],
						'messagelog'=>str_ireplace('Message content: £', '', $_GET['mcontent']).'\n');
		
		ob_start();
		ACARSData::UpdateFlightData($fields);
		
		$cont = ob_get_clean();
		
		ob_end_clean();
		
		writedebug($cont);
		
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
						'timeremaining'=>$_GET['timedestapt'],
						'phasedetail'=>$phase_detail[$_GET['detailph']],
						'online'=>$_GET['Online'],
						'client'=>'FSACARS');

		ob_start();
		
		ACARSData::UpdateFlightData($fields);
		$cont = ob_get_clean();
			
		ob_end_clean();
		
		writedebug($cont);
		
		break;
	
	#
	# File the PIREP
	#
	case 'pirep':

		writedebug("PIREP FILE");
		writedebug(print_r($_GET, true));
			
		$log = explode('*', $_GET['log']);
	
		
		# see if they are a valid pilot:
		preg_match('/^([A-Za-z]*)(\d*)/', $_GET['pilot'], $matches);
		print_r($matches);
		$pilotid = $matches[2];

		echo $pilotid;
		if(!($pilot = PilotData::GetPilotData($pilotid)))
		{
			return;
		}
		
		print_r($pilot);
		// match up the flight info
		preg_match('/^([A-Za-z]{2,3})(\d*)', $_GET['callsign'], $matches);
		$code = $matches[1];
		$flightnum = $matches[2];
		$depicao = $_GET['origin'];
		$arricao = $_GET['dest'];
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
		
		
		if($_GET['more'] == '1')
		{
			#
			# We have more coming to the log
			#
			
			$report = PIREPData::GetLastReports($pilotid, 1);
			PIREPData::AppendToLog($report->pirepid, $_GET['log']);	
		}
		else
		{
			#
			# Check if anything was in the log
			#	If not, then it probably wasn't a multi-chunk, so
			#	 just pull it straight from the query string
			#	Otherwise, pull the full-text from the session
			#
							
			$res = PIREPData::FileReport($data);
		}
		
		if(!$res)
			writedebug(DB::error());
			
		echo 'OK';
		break;
}

?>