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
	
	/**
	 * This generates the XML for the live ACARS map
	 */
	public function GenerateXML()
	{
		$output = '';
		$flights = self::GetACARSData(Config::Get('ACARS_LIVE_TIME'));
		
		$output = '<livemap>';
		
		foreach($flights as $flight)
		{
			#
			# Grab some basic info about the pilot
			#			
			preg_match('/^([A-Za-z]{2,3})(\d*)/', $flight->pilotid, $matches);
			$pilotid = $matches[2];
			$pilotinfo = PilotData::GetPilotData($pilotid);
			
			#
			# Start our output
			#
			
			$output.='<aircraft flightnum="'.$flight->flightnum.'" lat="'.$flight->lat.'" lng="'.$flight->lng.'">';
			
			
			#
			# Pilot and Route Information
			#
			
			$output.='<pilotid>'.$flight->pilotid.'</pilotid>';
			$output.='<pilotname>'. $pilotinfo->firstname.' '.$pilotinfo->lastname.'</pilotname>';
			$output.='<depicao>'.$flight->depicao.'</depicao>';
			$output.='<arricao>'.$flight->arricao.'</arricao>';
			$output.='<distremain>'.$flight->distremain.'</distremain>';
			$output.='<timeremain>'.$flight->timeremaining.'</timeremain>';
			
			#
			# Set the icon
			#
			$output.='<icon>';
			
			if($flight->phasedetail != 'Boarding' && $flight->phasedetail != 'Taxiing'
				&& $flight->phasedetail != 'FSACARS Closed' && $flight->phasedetail != 'Taxiiing to gate'
				&& $flight->phasedetail != 'Landed' && $flight->phasedetail != 'Arrived')
			{
				$output.=SITE_URL.'/lib/images/inair.png';
			}
			else
			{
				$output.=SITE_URL.'/lib/images/onground.png';
			}
			
			$output.='</icon>';
		
			
			#
			# Show their specific flight data
			#
			$output.='<details><![CDATA['
					.'<span style="font-size: 10px; text-align:left; width: 100%" align="left">'
					.'<a href="'.SITE_URL.'/index.php/profile/view/'.$flight->pilotid.'">'.$flight->pilotid.' - ' . $pilotinfo->firstname .' ' . $pilotinfo->lastname.'</a><br />'
					.'<strong>Flight '.$flight->flightnum.'</strong> ('.$flight->depicao.' to '.$flight->arricao.')<br />'
					.'<strong>Phase: </strong>'.$flight->phasedetail.'<br />'
					.'<strong>Dist/Time Remain: </strong>'.$flight->distremain.Config::Get('UNITS').'/'.$flight->timeremaining.' h:m<br />'
					.'</span>'
					.']]></details>';
			
			#
			# End the aircraft info
			#
			
			$output.='</aircraft>';
		}
		
		$output.='</livemap>';
		
		return $output;
	}
}
?>