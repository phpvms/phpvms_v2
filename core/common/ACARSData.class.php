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
	
	public static $lasterror;
	public static $fields = array('pilotid', 'flightnum', 'pilotname', 
								   'aircraft', 'lat', 'lng', 'heading', 
								   'alt', 'gs', 'depicao', 'depapt', 'arricao', 
								   'arrapt', 'deptime', 'arrtime', 'distremain', 'timeremaining',
								   'phasedetail', 'online', 'messagelog');
	
	
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
		
		if($data['depicao'] == '' || $data['arricao'] == ''
			|| $data['lat'] == '' || $data['lng'] == '')
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
	
		// first see if we exist:
		$exist = DB::get_row('SELECT `id`
								FROM '.TABLE_PREFIX.'acarsdata 
								WHERE `pilotid`=\''.$data['pilotid'].'\'');
		
		if($exist)
		{ // update
			
			$upd = '';
			// form the query
			foreach(self::$fields as $field)
			{
				// append the message log
				if($field == 'messagelog')
				{
					$upd.='`messagelog`=CONCAT(`messagelog`, \''.DB::escape($data['field']).'\'), ';
				}
				else 
				{
					// update only the included fields
					if(!isset($data[$field]))
					{
						continue;
					}
					
					// field=\'value\',
					$upd.=$field.'=\''.DB::escape($data[$field]).'\', ';
				}
			}
			
			// remove the extra , 
			//$upd = substr($upd, 0, strlen($upd)-1);
			$upd.='lastupdate=NOW() ';

			$query = 'UPDATE '.TABLE_PREFIX.'acarsdata 
						SET '.$upd.' 
						WHERE `pilotid`=\''.$data['pilotid'].'\'';
						
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
				
				$ins[$field] = $data[$field];
				$fields.='`'.$field.'`,';
				
				/*if(is_numeric($data[$field]))
				{
					$values.=$data[$field].', ';
				}
				else 
				{*/
					$values .= '\''.$data[$field].'\', ';
				//}
			}
			
			// last set
			$fields .=' lastupdate ';
			$values .= ' NOW()';
			
			$query = 'INSERT INTO '.TABLE_PREFIX.'acarsdata (
							'.$fields.') 
						VALUES ('.$values.')';
		
			DB::query($query);
			
		}
		
		
		CentralData::send_acars_data();
		return true;
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
		if($cutofftime == '')
			$cutofftime = Config::Get('ACARS_LIVE_TIME');
		
		$cutofftime = $cutofftime / 60;			
		//$time = strtotime('-'.$cutofftime .' hours');
				
		$sql = 'SELECT a.*, c.name as aircraftname,
					p.code, p.pilotid as pilotid, p.firstname, p.lastname
					FROM ' . TABLE_PREFIX .'acarsdata a
					LEFT JOIN '.TABLE_PREFIX.'aircraft c ON a.`aircraft`= c.`registration`
					LEFT JOIN '.TABLE_PREFIX.'pilots p ON a.`pilotid`= p.`pilotid`
					WHERE DATE_SUB(NOW(), INTERVAL '.$cutofftime.' HOUR) <= a.`lastupdate`';
		
		return DB::get_results($sql);
		
		return $ret;
	}
}