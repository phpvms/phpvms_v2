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
 
class ACARSData extends CodonModule
{
	
	function InsertData()
	{
		$pilotid = Vars::GET('pnumber');
		
		if($pilotid == '')
			return;
			
		$lat = Vars::GET('lat');
		$long = Vars::GET('long');
		$gs = Vars::GET('GS');
		$alt = Vars::GET('Alt');
		$IATA = Vars::GET('IATA');
		$depAptICAO = Vars::GET('depaptICAO');
		$depApt = Vars::GET('depapt');
		$disDepApt = Vars::GET('disdepapt');
		$timeDepApt = Vars::GET('timedepapt');
		$destAptICAO = Vars::GET('destaptICAO');
		$destApt = Vars::GET('destapt');
		$disDestApt = Vars::GET('disdestapt');
		$timeDestApt = Vars::GET('timedestapt');
		$phase = Vars::GET('detailph');
			
		$existing = DB::get_row('SELECT id FROM '.TABLE_PREFIX.'acarspos WHERE pilot_num="'.$pilotid.'"');
		
		//Do results, do a clean insert
		if(!$existing)
		{
			//argh, i hate using double quotes. but its a long query =\
			
			$sql = "INSERT INTO ".TABLE_PREFIX."acarsdata (pilot_num, lat, lon, gs, alt, IATA, depaptICAO, depapt,
						disDepApt, timeDepApt, destAptICAO, destApt, disDestApt, timeDestApt, phase)
					VALUES('$pilotid', '$lat', '$long', $gs, $alt, '$IATA', '$depAptICAO', '$depApt',
							$disDepApt, $timeDepApt, '$destAptICAO', '$destApt', $disDestApt, '$timeDestApt',
							'$phase')";
			
			$res = DB::query($sql);
		
			if(DB::errno() != 0)
				return false;
			
			return true;
		}
		else
		{
			//do an update
			
			$rowid = $existing->id;
			$sql = "UPDATE ".TABLE_PREFIX."acarsdata SET pilot_num='$pilotid', lat='$lat', lon='$lon', gs=$gs,
						alt=$alt, IATA='$IATA', depAptICAO='$depAptICAO', depApt='$depApt,
						disDepApt=$disDepApt, timeDepApt='timeDepApt, destAptICAO='$destAptICAO',
						destApt='$destApt', disDestApt=$disDestApt, timeDestApt=$timeDestApt, phase=$phase
					  WHERE id=".$rowid;
					
			$res = DB::query($sql);
			
			if(!$res)
			{
				//error out?
				
				//verbose for now
				
			}
			
		}
		
		// be verbose about any output for now
		
		DB::debug();
	}
	
	//TODO: convert this cutoff time into a SETTING parameter, in minutes
	function GetACARSData($cutofftime = 1)
	{
		//cutoff time in days
		if($cutofftime == '')
			$cutofftime = 1;
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX .'acarsdata
					WHERE DATE_SUB(NOW(), INTERVAL '.$cutofftime.' DAYS) <= last_update';
					
		return DB::get_results($sql);
	}
	
}

?>