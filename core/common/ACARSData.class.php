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
	
	public function UpdateFlightData($data)
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
		
		/*if($data['flightnum'] == '')
		{
			self::$lasterror = 'No flight number';
			return false;
		}*/

		// first see if we exist:
		$exist = DB::get_row('SELECT id FROM '.TABLE_PREFIX.'acarsdata WHERE pilotid=\''.$data['pilotid'].'\'');
		
		if($exist)
		{ // update
			
			$upd = '';
			// form the query
			foreach(self::$fields as $field)
			{
				// append the message log
				if($field == 'messagelog')
				{
					$upd.='messagelog=CONCAT(messagelog, \''.$data['field'].'\'), ';
				}
				else 
				{
					// update only the included fields
					if(!isset($data[$field]))
					{
						continue;
					}
					
					// field=\'value\',
					$upd.=$field.'=\''.$data[$field].'\', ';
				}
			}
			
			// remove the extra , 
			//$upd = substr($upd, 0, strlen($upd)-1);
			$upd.='lastupdate=NOW() ';

			$query = 'UPDATE '.TABLE_PREFIX.'acarsdata 
						SET '.$upd.' WHERE pilotid=\''.$data['pilotid'].'\'';
						
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
				$fields.=$field.',';
				
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
			
			$query = 'INSERT INTO '.TABLE_PREFIX.'acarsdata ('.$fields.') VALUES ('.$values.')';
		
			DB::query($query);
			
		}
		
		return true;
	}
	
	
	//TODO: convert this cutoff time into a SETTING parameter, in minutes
	public function GetACARSData($cutofftime = '')
	{
		//cutoff time in days
		if($cutofftime == '')
			$cutofftime = Config::Get('ACARS_LIVE_TIME');
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX .'acarsdata
					WHERE DATE_SUB(NOW(), INTERVAL '.$cutofftime.' HOUR) <= lastupdate';
					
		return DB::get_results($sql);
	}
}
?>