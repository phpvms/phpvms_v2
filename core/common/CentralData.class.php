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

class CentralData extends CodonData
{	
	public static $xml;
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
	
	private static function send_xml()
	{
		$web_service = new CodonWebService();
		self::$xml_response = $web_service->post(Config::Get('PHPVMS_API_SERVER').'/update', self::$xml->asXML());
			
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
		
	public static function set_xml($method)
	{
		self::$xml = new SimpleXMLElement('<vacentral/>');
		
		self::$xml->addChild('siteurl', SITE_URL);
		self::$xml->addChild('apikey', Config::Get('PHPVMS_API_KEY'));
		self::$xml->addChild('version', PHPVMS_VERSION);
		
		if(self::$debug === true)
		{
			self::$xml->addChild('debug', self::$debug);
		}
		
		self::$xml->addChild('method', $method);
	}
	
	public static function send_vastats()
	{
		if(!self::central_enabled())
			return false;

		self::set_xml('update_vainfo');
		self::$xml->addChild('pilotcount', StatsData::PilotCount());
		self::$xml->addChild('totalhours', StatsData::TotalHours());
		self::$xml->addChild('totalflights', StatsData::TotalFlights());
		self::$xml->addChild('totalschedules', StatsData::TotalSchedules());
				
		# Expenses stuff
		$exp_data = FinanceData::get_total_monthly_expenses();
		self::$xml->addChild('expenses', $exp_data->total);
		self::$xml->addChild('expensescost', $exp_data->cost);
		
		# Some of the settings
		self::$xml->addChild('livefuel', Config::Get('FUEL_GET_LIVE_PRICE'));
				
		# Package and send
		CronData::set_lastupdate('update_vainfo');		
		return self::send_xml();
	}	
	
	public static function send_schedules()
	{
		if(!self::central_enabled())
			return false;
		
		//$lastupdate = CronData::check_lastupdate('update_vainfo');
		//if($lastupdate->days == 0)
		//	return false;

		self::set_xml('update_schedules');
		
		$schedules = SchedulesData::GetSchedules('', true);
		
		if(!is_array($schedules))
			return false;
					
		foreach($schedules as $sched)
		{
			$schedule_xml = self::$xml->addChild('schedule');
		
			$schedule_xml->addChild('flightnum', $sched->code.$sched->flightnum);
			$schedule_xml->addChild('depicao', $sched->depicao);
			$schedule_xml->addChild('arricao', $sched->arricao);
			$schedule_xml->addChild('aircraft', $sched->aircraft);
			$schedule_xml->addChild('registration', $sched->registration);
			$schedule_xml->addChild('distance', $sched->distance);
			$schedule_xml->addChild('daysofweek', $sched->daysofweek);
			$schedule_xml->addChild('maxload', $sched->maxload);
			$schedule_xml->addChild('price', $sched->price);
			$schedule_xml->addChild('flighttype', $sched->flighttype);
			$schedule_xml->addChild('notes', $sched->notes);
			$schedule_xml->addChild('deptime', $sched->deptime);
			$schedule_xml->addChild('arrtime', $sched->arrtime);
		}		
		
				
		# Package and send
		CronData::set_lastupdate('update_schedules');
		return self::send_xml();
	}
	
	public function send_pilots()
	{
		if(!self::central_enabled())
			return false;
		
		self::set_xml('update_pilots');
		
		$allpilots = PilotData::GetAllPilots();
		self::$xml->addChild('total', count($allpilots));
		
		foreach($allpilots as $pilot)
		{
			$pilot_xml = self::$xml->addChild('pilot');
			$pilot_xml->addChild('pilotid', PilotData::GetPilotCode($pilot->code, $pilot->pilotid));
			$pilot_xml->addChild('pilotname', $pilot->firstname.' '.$pilot->lastname);
			$pilot_xml->addChild('location', $pilot->location);
		} 
		
		CronData::set_lastupdate('update_pilots');
		return self::send_xml();
	}
	
	public function send_all_pireps()
	{
		if(!self::central_enabled())
			return false;
		
		self::set_xml('update_pireps');
				
		$allpireps = PIREPData::GetAllReports();
		if(!$allpireps)
			return false;
		
		// Set them all to have not been exported
		PIREPData::setAllExportStatus(false);
		
		self::$xml->addChild('total', count($allpireps));
		
		foreach($allpireps as $pirep)
		{
			# Skip erronious entries
			if($pirep->aircraft == '')
				continue; 
			
			$xml .= self::get_pirep_xml($pirep);
		}
				
		CronData::set_lastupdate('update_pireps');
		$resp = self::send_xml();
		
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
		
		self::set_xml('add_pirep');
		
		$pirep = PIREPData::GetReportDetails($pirep_id);
		PIREPData::setExportedStatus($pirep_id, false);
		
		if(!$pirep)
			return false;
			
		$xml .= self::get_pirep_xml($pirep);
				
		CronData::set_lastupdate('add_pirep');
		$resp = self::send_xml();
		
		if($resp === true)
		{
			PIREPData::setExportedStatus($pirep_id, true);
			return true;
		}
	}
	
	protected function get_pirep_xml($pirep)
	{
		$pilotid = PilotData::GetPilotCode($pirep->code, $pirep->pilotid);
		
		$pirep_xml = self::$xml->addChild('pirep');
		$pirep_xml->addChild('uniqueid', $pirep->pirepid);
		$pirep_xml->addChild('pilotid', $pilotid);
		$pirep_xml->addChild('pilotname', $pirep->firstname.' '.$pirep->lastname);
		$pirep_xml->addChild('flightnum', $pirep->code.$pirep->flightnum);
		$pirep_xml->addChild('depicao', $pirep->depicao);
		$pirep_xml->addChild('arricao', $pirep->arricao);
		$pirep_xml->addChild('aircraft', $pirep->aircraft);
		$pirep_xml->addChild('registration', $pirep->registration);
		$pirep_xml->addChild('flighttime', $pirep->flighttime);
		$pirep_xml->addChild('submitdate', $pirep->submitdate);
		$pirep_xml->addChild('flighttype', $pirep->flighttype);
		$pirep_xml->addChild('load', $pirep->load);
		$pirep_xml->addChild('fuelused', $pirep->fuelused);
		$pirep_xml->addChild('fuelprice', $pirep->fuelprice);
		$pirep_xml->addChild('pilotpay', $pirep->pilotpay);
		$pirep_xml->addChild('price', $pirep->price);
		$pirep_xml->addChild('revenue', $pirep->revenue);
	}
	
	public static function send_all_acars()
	{
		if(!self::central_enabled())
			return false;
			
		$acars_flights = ACARSData::GetAllFlights();
		
		if(!$acars_flights)
			return false;
		
		self::set_xml('update_acars');
				
		foreach($acars_flights as $flight)
		{			
			self::create_acars_flight($flight);
		}
		
		CronData::set_lastupdate('update_acars');
		return self::send_xml();
	}
	
	public static function send_acars_data($flight)
	{
		if(!self::central_enabled())
			return false;
		
		self::set_xml('update_acars_flight');
		$xml .= self::create_acars_flight($flight);
		
		CronData::set_lastupdate('update_acars');
		return self::send_xml();
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
		
		$acars_xml = self::$xml->addChild('flight');
		$acars_xml->addChild('unique_id', $flight['id']);
		$acars_xml->addChild('pilotid', PilotData::GetPilotCode($flight['code'], $flight['pilotid']));
		$acars_xml->addChild('pilotname', $flight['firstname'].' '.$flight['lastname']);
		$acars_xml->addChild('flightnum', $flight['flightnum']);
		$acars_xml->addChild('aircraft', $flight['aircraft']);
		$acars_xml->addChild('lat', $flight['lat']);
		$acars_xml->addChild('lng', $flight['lng']);
		$acars_xml->addChild('depicao', $flight['depicao']);
		$acars_xml->addChild('arricao', $flight['arricao']);
		$acars_xml->addChild('deptime', $flight['deptime']);
		$acars_xml->addChild('arrtime', $flight['arrtime']);
		$acars_xml->addChild('heading', $flight['heading']);
		$acars_xml->addChild('phase',$flight['phasedetail']);
		$acars_xml->addChild('alt', $flight['alt']);
		$acars_xml->addChild('gs', $flight['gs']);
		$acars_xml->addChild('distremain', $flight['distremain']);
		$acars_xml->addChild('timeremain', $flight['timeremaining']);
		$acars_xml->addChild('client', $flight['client']);
		$acars_xml->addChild('lastupdate', $flight['lastupdate']);
	}
}