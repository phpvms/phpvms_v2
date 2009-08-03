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

	static $xml_data = '';
	static $debug = false;
	
	private static function central_enabled()
	{
		if(Config::Get('PHPVMS_CENTRAL_ENABLED')
			&& Config::Get('PHPVMS_API_KEY') != '')
			return true;
		
		return false;
	}
	
	private static function send_xml($xml)
	{
		self::$xml_data = '<?xml version="1.0"?>'.$xml;
		$web_service = new CodonWebService();
		$res = $web_service->post(Config::Get('PHPVMS_API_SERVER').'/index.php/update', self::$xml_data);
			
		return $res;		
	}
		
	private static function xml_header($method)
	{
		$xml = '<siteurl>'.SITE_URL.'</siteurl>'.PHP_EOL;
		$xml .= '<apikey>'.Config::Get('PHPVMS_API_KEY').'</apikey>'.PHP_EOL;
		$xml .= '<method>'.$method.'</method>'.PHP_EOL;
		return $xml;
	}
	
	public static function send_vastats()
	{
		if(!self::central_enabled())
			return false;
			
		/*$lastupdate = CronData::check_lastupdate('update_vainfo');
		if($lastupdate->days == 0)
			return false;*/
			
		$xml = '<vainfo>';
		$xml .= self::xml_header('update_vainfo');
		$xml .= '<pilotcount>'.StatsData::PilotCount().'</pilotcount>';
		$xml .= '<totalhours>'.StatsData::TotalHours().'</totalhours>';
		$xml .= '<totalflights>'.StatsData::TotalFlights().'</totalflights>';
		
		# Expenses stuff
		$exp_data = FinanceData::get_total_monthly_expenses();
		
		$xml .= '<expenses>'.$exp_data->total.'</expenses>';
		$xml .= '<expensescost>'.$exp_data->cost.'</expensescost>';
		
		# Some of the settings
		$xml .= '<livefuel>'.Config::Get('FUEL_GET_LIVE_PRICE').'</livefuel>';
		
		$xml .= '</vainfo>';
		
		# Package and send
		CronData::set_lastupdate('update_vainfo');		
		return self::send_xml($xml);
	}	
	
	public static function send_schedules()
	{
		if(!self::central_enabled())
			return false;
		
		//$lastupdate = CronData::check_lastupdate('update_vainfo');
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
						|| $name == 'depname' || $name == 'arrname' )
				{
					continue;
				}
				
				$xml .= "<{$name}>{$val}</{$name}>".PHP_EOL;
			}
			
			$xml .= '</schedule>'.PHP_EOL;
		}		
		
		$xml .= '</schedules>';
				
		# Package and send
		CronData::set_lastupdate('update_schedules');
		return self::send_xml($xml);
	}
	
	public function send_pilots()
	{
		if(!self::central_enabled())
			return false;
		
		$xml = '<pilotdata>'.PHP_EOL;
		$xml .= self::xml_header('update_pilots');
		
		$allpilots = PilotData::GetAllPilots();
		
		$xml .= '<total>'.count($allpilots).'</total>';
		
		foreach($allpilots as $pilot)
		{
			$xml.='<pilot>'
				 .'<pilotid>'.PilotData::GetPilotCode($pilot->code, $pilot->pilotid).'</pilotid>'
				 .'<pilotname>'.$pilot->firstname.' '.$pilot->lastname.'</pilotname>'
				 .'<location>'.$pilot->location.'</location>'
				 .'</pilot>';
		} 
		
		$xml.='</pilotdata>';
		
		CronData::set_lastupdate('update_pilots');
		return self::send_xml($xml);	
	}
	
	public function send_all_pireps()
	{
		if(!self::central_enabled())
			return false;
		
		$xml = '<pirepdata>'.PHP_EOL;
		$xml .= self::xml_header('update_pireps');
		
		
		$allpireps = PIREPData::GetAllReports();
		$xml .= '<total>'.count($allpireps).'</total>';
		
		foreach($allpireps as $pirep)
		{
			# Skip erronious entries
			if($pirep->aircraft == '')
				continue; 
				
			$xml .= '<pirep>'
					.'<pilotid>'.PilotData::GetPilotCode($pirep->code, $pirep->pilotid).'</pilotid>'
					.'<pilotname>'.$pirep->firstname.' '.$pirep->lastname.'</pilotname>'
					.'<flightnum>'.$pirep->code.$pirep->flightnum.'</flightnum>'
					.'<depicao>'.$pirep->depicao.'</depicao>'
					.'<arricao>'.$pirep->arricao.'</arricao>'
					.'<aircraft>'.$pirep->aircraft.'</aircraft>'
					.'<flighttime>'.$pirep->flighttime.'</flighttime>'
					.'<submitdate>'.$pirep->submitdate.'</submitdate>'
					.'<flighttype>'.$pirep->flighttype.'</flighttype>'
					.'<load>'.$pirep->load.'</load>'
					.'<fuelused>'.$pirep->fuelused.'</fuelused>'
					.'<fuelprice>'.$pirep->fuelprice.'</fuelprice>'
					.'<pilotpay>'.$pirep->pilotpay.'</pilotpay>'
					.'<price>'.$pirep->price.'</price>'
					.'</pirep>';
		}
		
		$xml .= '</pirepdata>';
		
		CronData::set_lastupdate('update_pireps');
		return self::send_xml($xml);	
	}
	
	public function send_pirep($pirep_id)
	{
		if(!self::central_enabled())
			return false;
		
		$xml = '<pirepdata>'.PHP_EOL;
		$xml .= self::xml_header('add_pirep');
		
		$pirep = PIREPData::GetReportDetails($pirep_id);
				
		$xml .= '<pirep>'
				.'<pilotid>'.PilotData::GetPilotCode($pirep->code, $pirep->pilotid).'</pilotid>'
				.'<pilotname>'.$pirep->firstname.' '.$pirep->lastname.'</pilotname>'
				.'<flightnum>'.$pirep->code.$pirep->flightnum.'</flightnum>'
				.'<depicao>'.$pirep->depicao.'</depicao>'
				.'<arricao>'.$pirep->arricao.'</arricao>'
				.'<aircraft>'.$pirep->aircraft.'</aircraft>'
				.'<flighttime>'.$pirep->flighttime.'</flighttime>'
				.'<submitdate>'.$pirep->submitdate.'</submitdate>'
				.'<flighttype>'.$pirep->flighttype.'</flighttype>'
				.'<load>'.$pirep->load.'</load>'
				.'<fuelused>'.$pirep->fuelused.'</fuelused>'
				.'<fuelprice>'.$pirep->fuelprice.'</fuelprice>'
				.'<pilotpay>'.$pirep->pilotpay.'</pilotpay>'
				.'<price>'.$pirep->price.'</price>'
				.'</pirep>';
		
		$xml .= '</pirepdata>';
		
		CronData::set_lastupdate('add_pirep');
		return self::send_xml($xml);		
	}
	
	public static function send_acars_data()
	{
		if(!self::central_enabled())
			return false;
		
		$xml = '<acarsdata>'.PHP_EOL;
		$xml .= self::xml_header('update_acars');
		
		$acars_flights = ACARSData::GetACARSData();
			
		if(!$acars_flights)
			return false;
		
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
		
		CronData::set_lastupdate('update_acars');
		return self::send_xml($xml);
	}	
}