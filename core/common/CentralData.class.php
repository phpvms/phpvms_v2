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

class CentralData
{
	
	private function check_lastupdate($name)
	{
		$name = strtoupper($name);
		$sql = 'SELECT *, DATEDIFF(NOW(), lastupdate) AS days
				 FROM '.TABLE_PREFIX."updates
				 WHERE name='{$name}'";
				 
		return DB::get_row($sql);
	}

	
	private function set_lastupdate($name)
	{
		$name = strtoupper($name);
		if(!self::check_lastupdate($name))
		{
			$sql = "INSERT INTO ".TABLE_PREFIX."updates
							(name, lastupdate)
					VALUES	('{$name}', NOW())";
		}
		else
		{
			$sql = "UPDATE ".TABLE_PREFIX."updates
						SET lastupdate=NOW()
						WHERE name='{$name}'";
		}
		
		DB::query($sql);
	}
	
	private function central_enabled()
	{
		if(Config::Get('PHPVMS_CENTRAL_ENABLED')
			&& Config::Get('PHPVMS_API_KEY') != '')
			return true;
		
		return false;
	}
	
	private function send_xml($xml)
	{
		$xml = '<?xml version="1.0"?>'.$xml;
		$web_service = new CodonWebService();
		$res = $web_service->post(Config::Get('PHPVMS_API_SERVER').'/index.php/update', $xml);
	
		echo $res;		
	}
		
	private function xml_header($method)
	{
		$xml = '<siteurl>'.SITE_URL.'</siteurl>'.PHP_EOL;
		$xml .= '<apikey>'.Config::Get('PHPVMS_API_KEY').'</apikey>'.PHP_EOL;
		$xml .= '<method>'.$method.'</method>'.PHP_EOL;
		return $xml;
	}
	
	public function send_vastats()
	{
		if(!self::central_enabled())
			return false;
			
		/*$lastupdate = self::check_lastupdate('update_vainfo');
		if($lastupdate->days == 0)
			return false;*/
			
		$xml = '<vainfo>';
		$xml .= self::xml_header('update_vainfo');
		$xml .= '<pilotcount>'.StatsData::PilotCount().'</pilotcount>';
		$xml .= '<totalhours>'.StatsData::TotalHours().'</totalhours>';
		$xml .= '<totalflights>'.StatsData::TotalFlights().'</totalflights>';
		$xml .= '</vainfo>';
		
		# Package and send
		self::send_xml('schedules', $xml);
		self::set_lastupdate('update_vainfo');		
	}	
	
	public function send_schedules()
	{
		if(!self::central_enabled())
			return false;
		
		//$lastupdate = self::check_lastupdate('update_vainfo');
		//if($lastupdate->days == 0)
		//	return false;
			
		$xml = '<schedules>'.PHP_EOL;
		$xml .= self::xml_header('update_schedules');
		
		$schedules = SchedulesData::GetSchedules('', true);
		
		if(!is_array($schedules))
			return false;
			
		foreach($schedules as $sched)
		{
			$vars = get_object_vars($sched);
			
			$xml .= '<schedule>'.PHP_EOL;
			foreach($vars as $name=>$val)
			{
				$val = strip_tags($val);
				
				if($name == 'id' || $name == 'leg' || $name == 'enabled'
						|| $name == 'flighttime' || $name == 'timesflown'
						|| $name == 'depname' || $name == 'deplat'
						|| $name == 'deplong' || $name == 'arrname' 
						|| $name == 'arrlat' || $name == 'arrlong')
				{
					continue;
				}
				
				$xml .= "<{$name}>{$val}</{$name}>".PHP_EOL;
			}
			
			$xml .= '</schedule>'.PHP_EOL;
		}		
		
		$xml .= '</schedules>';
		
		# Package and send
		self::send_xml($xml);
		self::set_lastupdate('update_schedules');
	}
	
	public function send_acars_data()
	{
		if(!self::central_enabled() && !is_array($acars_data))
			return false;
		
		$xml = '<acarsdata>'.PHP_EOL;
		$xml .= self::xml_header('update_acars');
		
		$acars_flights = ACARSData::GetACARSData();
		foreach($acars_flights as $flight)
		{
			$xml.='<flight>';
			
			$xml.='<aircraft>'.$flight->aircraftname.'</aircraft>'
				.'<flightnum>'.$flight->flightnum.'</flightnum>'
				.'<lat>'.$flight->lat.'</lat>'
				.'<lng>'.$flight->lng.'</lng>'
				.'<pilotid>'.PilotData::GetPilotCode($flight->code, $flight->pilotid).'</pilotid>'
				.'<pilotname>'. $flight->firstname.' '.$flight->lastname.'</pilotname>'
				.'<depicao>'.$flight->depicao.'</depicao>'
				.'<arricao>'.$flight->arricao.'</arricao>'
				.'<deptime>'.$flight->deptime.'</deptime>'
				.'<arrtime>'.$flight->arrtime.'</arrtime>'
				.'<heading>'.$flight->heading.'</heading>'
				.'<phase>'.$flight->phasedetail.'</phase>'
				.'<alt>'.$flight->alt.'</alt>'
				.'<gs>'.$flight->gs.'</gs>'
				.'<distremain>'.$flight->distremain.'</distremain>'
				.'<timeremaining>'.$flight->timeremaining.'</timeremaining>'
				.'<lastupdate>'.$flight->lastupdate.'</lastupdate>'
				.'</flight>';
		}
		
		$xml .= '</acarsdata>';
		
		self::send_xml($xml);
	}	
}