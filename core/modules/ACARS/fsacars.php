<?php

/**
 * phpVMS ACARS integration
 *
 * Interface for use with FSACARS
 * http://www.satavirtual.org/fsacars/
 */

// Our flight phase constants:
$phase_short = array('null', 'Boarding', 'Departing', 'Cruise', 'Arrived');

$phase_detail = array('FSACARS Closed', 'Boarding', 'Taxiing', 'Takeoff', 'Climbing',
				 	  'Cruise', 'Landing Shortly', 'Landed', 'Taxiiing to gate', 'Arrived');

$flightcargo = array('Pax', 'Cargo');

// Determine the message type, based on what is passed:

	// ACARS message (status change, etc)
	if($_GET['mcontent'] != '')
	{
		$pilotid = $_GET['pilotnumber'];
		
		$fields = array('pilotid'=>$_GET['pilotnumber'],
						'messagelog'=>str_ireplace('Message content: £', '', $_GET['mcontent']).'\n');
		
		ob_start();
		ACARSData::UpdateFlightData($fields);
		
		$cont = ob_get_clean();
		ob_end_clean();
	}
	
	// Position Update
	if($_GET['lat']  != '' && $_GET['long'] != '')
	{
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
	}

	// PIREP being filed
	if($_GET['pilotnumber'] != '' && $_GET['dur'] != '' && $_GET['len'] != '')
	{
		// see if they are a valid pilot:
		preg_match('/^([A-Za-z]{2,3})(\d*)', $_GET['pilotnumber'], $matches);
		$pilotid = $matches[2];
		
		if(!($pilot = PilotData::GetPilotData($pilotid)))
		{
			return;
		}
		
		// match up the flight info
		preg_match('/^([A-Za-z]{2,3})(\d*)', $_GET['callsign'], $matches);
		$code = $matches[1];
		$flightnum = $matches[2];
		$depicao = $_GET['depart'];
		$arricao = $_GET['arrival'];
		$aircraft = $_GET['equipment'];
		$flighttime = $_GET['duration'];
		$comment = "";
		$log = $_GET['log'];
		
		PIREPData::FileReport($pilotid, $code,$flightnum, $depicao, $arricao, $aircraft, $flighttime, $comment, $log);
	}
	
echo 'OK';
?>