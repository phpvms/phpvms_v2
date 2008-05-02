<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 *  This program is free software; you can redistribute it and/or modify it 
 *  under the terms of the GNU General Public License as published by the Free 
 *	Software Foundation; either version 2 of the License, or (at your option) 
 *	any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT 
 *  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 *	FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with 
 *	this program; if not, write to the:
 *		Free Software Foundation, Inc., 
 *		59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license GNU Public License http://opensource.org/licenses/gpl-license.php
 * @package core_api
 */
 
class ACARSData 
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
			
			if(!$res)
			{
		
			}
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