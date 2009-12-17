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
 
class ACARSData extends CodonData
{
	
	public static $lasterror;
	public static $pirepid;
	public static $fields = array('pilotid', 'flightnum', 'pilotname', 
								   'aircraft', 'lat', 'lng', 'heading', 
								   'alt', 'gs', 'depicao', 'arricao', 
								   'deptime', 'arrtime', 'distremain', 'timeremaining',
								   'phasedetail', 'online', 'messagelog', 'client');
	
	
	/**
	 * This updates the ACARS live data for a pilot
	 *
	 * @param mixed $data This is the data structure with flight properties
	 * @return mixed Nothing
	 *
	 */
	public static function UpdateFlightData($data)
	{
		if(!is_array($data))
		{
			self::$lasterror = 'Data not array';
			return false;
		}
		
		// make sure at least the vitals we need are there:
		if($data['pilotid'] == '')
		{
			self::$lasterror = 'No pilot ID specified';
			return false;
		}
		
		if($data['flightnum'] == '')
		{
			self::$lasterror = 'No flight number';
			return false;
		}
		
		if($data['depicao'] == '' || $data['arricao'] == '' || $data['lat'] == '' || $data['lng'] == '')
		{
			self::$lasterror = 'Airports are blank';
			return;
		}

		if(!is_numeric($data['pilotid']))
		{
			preg_match('/^([A-Za-z]*)(\d*)/', $data['pilotid'], $matches);
			$code = $matches[1];
			$data['pilotid'] = $matches[2];
		}
		
		if(isset($data['code']))
		{
			$data['flightnum'] = $data['code'].$data['flightnum'];
		}
		
		// Add pilot info		
		$pilotinfo = PilotData::GetPilotData($data['pilotid']);
		$data['firstname'] = $pilotinfo->firstname;
		$data['lastname'] = $pilotinfo->lastname;
		$data['pilotname'] = $pilotinfo->firstname . ' ' . $pilotinfo->lastname;
		
		// Get the airports data
		$dep_apt = OperationsData::GetAirportInfo($data['depicao']);
		$arr_apt = OperationsData::GetAirportInfo($data['arricao']);
		$dep_apt->name = DB::escape($dep_apt->name);
		$arr_apt->name = DB::escape($arr_apt->name);
		
		// Clean up times
		if(!is_numeric($data['deptime']))
			$data['deptime'] = strtotime($data['deptime']);
			
		if(!is_numeric($data['arrtime']))
			$data['arrtime'] = strtotime($data['arrtime']);
		
		/* Check the heading for the flight
			If none is specified, then point it straight to the arrival airport */
		if($data['heading'] == '' || $data['heading'] == null || !isset($data['heading']))
		{
			/* Calculate an angle based on current coords and the
				destination coordinates */
			
			$data['heading'] = intval(atan2(($data['lat'] - $arr_apt->lat), ($data['lng'] - $arr_apt->lng)) * 180/3.14);
			//$flight->heading *= intval(180/3.14159);
			
			if(($data['lat'] - $data['lng']) < 0)
			{
				$data['heading'] += 180;
			}
			
			if($data['heading'] < 0)
			{
				$data['heading'] += 360;
			}
		}
	
		// first see if we exist:
		$exist = DB::get_row('SELECT `id`
								FROM '.TABLE_PREFIX.'acarsdata 
								WHERE `pilotid`=\''.$data['pilotid'].'\'');
			
		$flight_id = '';
		if($exist)
		{ // update
			
			$flight_id = $exist->id;
			$upd = '';
			
			foreach(self::$fields as $field)
			{
				// update only the included fields
				if(!isset($data[$field]))
				{
					continue;
				}
				
				$data[$field] = DB::escape($data[$field]);
				// Append the message log
				if($field == 'messagelog')
				{
					$upd.="`messagelog`=CONCAT(`messagelog`, '{$data[$field]}'), ";
				}
				// Update times
				elseif($field == 'deptime' || $field == 'arrtime')
				{
					/* If undefined, set a default time to now (penalty for malformed data?)
						Won't be quite accurate.... */
					if($data[$field] == '') $data[$field] = time();
					
					$upd.="`{$field}`=FROM_UNIXTIME(".$data[$field]."), ";
				}
				else 
				{					
					$upd.="`{$field}`='".$data[$field]."', ";
				}
			}
			
			// Update Airports	
			$upd .= " `depapt`='{$dep_apt->name}', `arrapt`='{$arr_apt->name}', lastupdate=NOW()";

			$query = 'UPDATE '.TABLE_PREFIX."acarsdata 
						SET {$upd} 
						WHERE `pilotid`='{$data['pilotid']}'";
						
			DB::query($query);
		}
		else
		{
			// insert
			
			// form array with $ins[column]=value and then
			//	give it to quick_insert to finish
			$ins = array();
			$fields='';
			$values='';
			foreach(self::$fields as $field)
			{
				// field=\'value\',
				// add only fields which are present
				if(!isset($data[$field]))
				{
					continue;
				}
				
				$fields.="`{$field}`, ";
				
				if($field == 'deptime' || $field == 'arrtime')
				{
					if($data[$field] == '') $data[$field] = time();
					$values .= "FROM_UNIXTIME(".$data[$field]."), ";
				}
				else
				{
					$ins[$field] = $data[$field];
					$values .= "'".$data[$field]."', ";
				}
			}
			
			// Manually add the last set
			$fields .=' `lastupdate`, `depapt`, `arrapt` ';
			$values .= " NOW(), '{$dep_apt->name}', '{$arr_apt->name}'";
			
			$query = 'INSERT INTO '.TABLE_PREFIX.'acarsdata (
							'.$fields.') 
						VALUES ('.$values.')';
		
			DB::query($query);
			
			$data['deptime'] = time();
			$flight_id = DB::$insert_id;
		}
		
		// Add this cuz we need it
		$data['unique_id'] = $flight_id;
			
		$res = CentralData::send_acars_data($data);
		return true;
	}
	
	public static function get_flight_by_pilot($pilotid)
	{
		$pilotid = intval($pilotid);
		$sql = 'SELECT * FROM '.TABLE_PREFIX."acarsdata 
					WHERE `pilotid`='{$pilotid}'";
		
		return DB::get_row($sql);		
	}
	
	public static function get_flight($code, $flight_num)
	{
		$code = DB::escape($code);
		$flight_num = DB::escape($flight_num);
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX."acarsdata 
					WHERE flightnum='{$code}{$flight_num}'";
		
		return DB::get_row($sql);		
	}
	
	/**
	 * File a PIREP from an ACARS program
	 *
	 * @param mixed $pilotid The pilot ID of the pilot filing the PIREP
	 * @param mixed $data This is the data structure with the PIREP info
	 * @return bool true/false
	 *
	 */
	public static function FilePIREP($pilotid, $data)
	{
		if(!is_array($data)) {
			self::$lasterror = 'PIREP data must be array';
			return false;
		}
		
		# Call the pre-file event
		#
		if(CodonEvent::Dispatch('pirep_prefile', 'PIREPS', $_POST) == false)
		{
			return false;
		}
		
		# File the PIREP report
		#  
		$ret = PIREPData::FileReport($data);
		
		if(!$ret)
			return false;
			
		self::$pirepid = DB::$insert_id;
		
		# Call the event
		#
		CodonEvent::Dispatch('pirep_filed', 'PIREPS', $_POST);
		
		# Close out a bid if it exists
		#
		$bidid = SchedulesData::GetBidWithRoute($pilotid, $data['code'], $data['flightnum']);
		if($bidid)
		{
			SchedulesData::RemoveBid($bidid->bidid);
		}
		
		return true;
	}
	
	public static function GetAllFlights()
	{
		$sql = 'SELECT a.*, c.name as aircraftname,
					p.code, p.pilotid as pilotid, p.firstname, p.lastname
					FROM ' . TABLE_PREFIX .'acarsdata a
					LEFT JOIN '.TABLE_PREFIX.'aircraft c ON a.`aircraft`= c.`registration`
					LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = a.depicao
					LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = a.arricao
					LEFT JOIN '.TABLE_PREFIX.'pilots p ON a.`pilotid`= p.`pilotid`';
		
		return DB::get_results($sql);
	}

	
	/**
	 * This returns all of the current ACARS flights within the cutoff
	 *
	 * @param int $cutofftime This is the cut-off time in hours (12 hours return in)
	 * @return array Returns an array of objects with the ACARS data
	 *
	 */
	public static function GetACARSData($cutofftime = '')
	{
		//cutoff time in days
		if($cutofftime == '' && $cutofftime != null)
		{
			// Go from minutes to hours
			$cutofftime = Config::Get('ACARS_LIVE_TIME') / 60;
			//$cutofftime = $cutofftime / 60;			
		}
		else
		{
			$cutofftime = 12;
		}
		
		/*$sql = "DELETE FROM ".TABLE_PREFIX."acarsdata a
					WHERE DATE_SUB(NOW(), INTERVAL '.$cutofftime.' HOUR) > a.`lastupdate`'";
		
		DB::query($sql);
		*/	
		
		$sql = 'SELECT a.*, c.name as aircraftname,
					p.code, p.pilotid as pilotid, p.firstname, p.lastname,
					dep.name as depname, dep.lat AS deplat, dep.lng AS deplng,
					arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlng
				FROM ' . TABLE_PREFIX .'acarsdata a
				LEFT JOIN '.TABLE_PREFIX.'aircraft c ON a.`aircraft`= c.`registration`
				LEFT JOIN '.TABLE_PREFIX.'pilots p ON a.`pilotid`= p.`pilotid`
				LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = a.depicao
				LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = a.arricao
				WHERE DATE_SUB(NOW(), INTERVAL '.$cutofftime.' HOUR) <= a.`lastupdate`';
		
		return DB::get_results($sql);
	}
}