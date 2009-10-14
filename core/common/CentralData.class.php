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

	public static $xml_data = '';
	public static $xml_response = '';
	public static $debug = false;
	public static $response;
	public static $last_error;
	
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
		self::$xml_response = $web_service->post(Config::Get('PHPVMS_API_SERVER').'/update', self::$xml_data);
			
		self::$response = @simplexml_load_string(self::$xml_response);
		
		if(!is_object(self::$response))
		{
			self::$last_error = 'No response from API server';
			return false;
		}
		
		if((int)self::$response->responsecode != 200)
		{
			self::$last_error = self::$response->message->detail;
			return false;
		}
		
		return true;
	}
		
	private static function xml_header($method)
	{
		$xml = '';
		$xml .= '<siteurl>'.SITE_URL.'</siteurl>'.PHP_EOL;
		$xml .= '<apikey>'.Config::Get('PHPVMS_API_KEY').'</apikey>'.PHP_EOL;
		$xml .= '<version>'.PHPVMS_VERSION.'</version>'.PHP_EOL;
		
		if(self::$debug === true)
		{
			$xml .= '<debug>'.self::$debug.'</debug>'.PHP_EOL;
		}
			
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
		if(!$allpireps)
			return false;
		
		// Set them all to have not been exported
		PIREPData::setAllExportStatus(false);
		
		$xml .= '<total>'.count($allpireps).'</total>';
		
		foreach($allpireps as $pirep)
		{
			# Skip erronious entries
			if($pirep->aircraft == '')
				continue; 
			
			$xml .= self::get_pirep_xml($pirep);
		}
		
		$xml .= '</pirepdata>';
		
		CronData::set_lastupdate('update_pireps');
		$resp = self::send_xml($xml);	
		
		// Only if we get a valid response, set the PIREPs to exported
		if($resp === true)
		{
			PIREPData::setAllExportStatus(true);
			return true;
		}
	}
	
	public function send_pirep($pirep_id)
	{
		if(!self::central_enabled())
			return false;
			
		if($pirep_id == '')
		{
			return;
		}
		
		$xml = '<pirepdata>'.PHP_EOL;
		$xml .= self::xml_header('add_pirep');
		
		$pirep = PIREPData::GetReportDetails($pirep_id);
		PIREPData::setExportedStatus($pirep_id, false);
		
		if(!$pirep)
			return false;
			
		$xml .= self::get_pirep_xml($pirep);
		
		$xml .= '</pirepdata>';
		
		CronData::set_lastupdate('add_pirep');
		$resp = self::send_xml($xml);	
		
		if($resp === true)
		{
			PIREPData::setExportedStatus($pirep_id, true);
			return true;
		}
	}
	
	protected function get_pirep_xml($pirep)
	{
		$pilotid = PilotData::GetPilotCode($pirep->code, $pirep->pilotid);
		
		return '<pirep>'
				.'<uniqueid>'.$pirep->pirepid.'</uniqueid>'
				.'<pilotid>'.$pilotid.'</pilotid>'
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
				.'<revenue>'.$pirep->revenue.'</revenue>'
				.'</pirep>';		
	}
	
	public static function send_all_acars()
	{
		if(!self::central_enabled())
			return false;
			
		$acars_flights = ACARSData::GetAllFlights();
		
		if(!$acars_flights)
			return false;
		
		$xml = '<acarsdata>'.PHP_EOL;
		$xml .= self::xml_header('update_acars');
				
		foreach($acars_flights as $flight)
		{			
			$xml .= self::create_acars_flight($flight);
		}
		
		$xml .= '</acarsdata>';
		CronData::set_lastupdate('update_acars');
		return self::send_xml($xml);
	}
	
	public static function send_acars_data($flight)
	{
		if(!self::central_enabled())
			return false;
		
		$xml = '<acarsdata>'.PHP_EOL;
		$xml .= self::xml_header('update_acars_flight');
		$xml .= self::create_acars_flight($flight);
		$xml .= '</acarsdata>';
		
		CronData::set_lastupdate('update_acars');
		return self::send_xml($xml);
	}	
	
	protected function create_acars_flight($flight)
	{
		if(is_object($flight))
		{
			$flight = (array) $flight;
		}
		
		// If a unique was specified
		if(isset($flight['unique_id']))
		{
			$flight['id'] = $flight['unique_id'];
		}
				
		return '<flight>'
				.'<unique_id>'.$flight['id'].'</unique_id>'
				.'<client>'.$flight['client'].'</client>'
				.'<flightnum>'.$flight['flightnum'].'</flightnum>'
				.'<aircraft>'.$flight['aircraft'].'</aircraft>'
				.'<lat>'.$flight['lat'].'</lat>'
				.'<lng>'.$flight['lng'].'</lng>'
				.'<pilotid>'.PilotData::GetPilotCode($flight['code'], $flight['pilotid']).'</pilotid>'
				.'<pilotname>'. $flight['firstname'].' '.$flight['lastname'].'</pilotname>'
				.'<depicao>'.$flight['depicao'].'</depicao>'
				.'<arricao>'.$flight['arricao'].'</arricao>'
				.'<deptime>'.$flight['deptime'].'</deptime>'
				.'<arrtime>'.$flight['arrtime'].'</arrtime>'
				.'<heading>'.$flight['heading'].'</heading>'
				.'<phase>'.$flight['phasedetail'].'</phase>'
				.'<alt>'.$flight['alt'].'</alt>'
				.'<gs>'.$flight['gs'].'</gs>'
				.'<distremain>'.$flight['distremain'].'</distremain>'
				.'<timeremain>'.$flight['timeremaining'].'</timeremain>'
				.'<lastupdate>'.$flight['lastupdate'].'</lastupdate>'
			  .'</flight>';
	}
}