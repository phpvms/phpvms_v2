<?php
error_reporting(E_ALL ^ E_NOTICE);

/** 
 * Test file
 *	DO NOT LEAVE IN PRODUCTION
 * 
 * Used to test API functionality
 */
include 'core/codon.config.php';


DB::debug();

class UnitTestSuite
{
	static $time_profile = true;
	public static function run()
	{		
		CentralData::$debug = true;
		
		#self::pay_test();
		
		self::pirep_times_test();
		
		#self::fuel_price();	
		
		// Control what test we are running
		self::vacentral_test();
	}
	
	public static function pay_test()
	{
		echo PilotData::get_pilot_pay(3.51, 15).'<br />';
		echo PilotData::get_pilot_pay(1.11, 15).'<br />';
		echo PilotData::get_pilot_pay(100, 10).'<br />';
	}
	
	public static function pirep_times_test()
	{
		
		$query = "SELECT * FROM phpvms_pireps where accepted=1";
		$results = DB::get_results($query);
		
		Util::$trace = true;
		$time = 0;
		foreach($results as $row)
		{
			$time = Util::AddTime($time, $row->flighttime);
			var_dump(Util::$trace);
		}
		
		
		
		echo "Total flight hours: {$time}<br /><br />";
	}
	
	/* All the tests go under here
		Pick and choose what to run in ::run()
	 */
	 
	public static function fuel_price()
	{
		// check international airport
		echo "LFPG Live Price: ";
		echo FuelData::GetFuelPrice('LFPG');
		echo '<br />KJFK Live Price: ';
		// check usa airport (live price)
		echo FuelData::GetFuelPrice('KJFK');
		//disable live
		Config::Set('FUEL_GET_LIVE_PRICE', false);
		// check usa airport (live price)
		echo '<br />KJFK Set Price: ';
		echo FuelData::GetFuelPrice('KJFK');
		echo '<br />';
	}
	
	public static function vacentral_test()
	{
		
		CentralData::$debug = true;
		$resp = CentralData::send_vastats();
		echo CentralData::$xml_data;
		echo $resp;
	}
	
	public static function vacentral_sendpirep()
	{
		$resp = CentralData::send_pirep(105);
		echo CentralData::$xml_data;
		echo $resp;
	}
	
	public static function vacentral_sendpilots()
	{
		$resp = CentralData::send_pilots();
		echo CentralData::$xml_data;
		echo $resp;
	}
	
	public static function test_times()
	{
		$time = Util::AddTime(1, 1);
		$time = Util::AddTime($time, .55);
		$time = Util::AddTime($time, .5);
		$time = Util::AddTime($time, .5);
		$time = Util::AddTime($time, 0.5);
		$time = Util::AddTime($time, .35);
		$time = Util::AddTime($time, 1.1);
		$time = Util::AddTime($time, 1.11);
		$time = Util::AddTime($time, 3);
		$time = Util::AddTime($time, 2.52);
		$time = Util::AddTime($time, .58);
		$time = Util::AddTime($time, .59);
		$time = Util::AddTime($time, .53);
		$time = Util::AddTime($time, .59);
		$time = Util::AddTime($time, 1.02);
		
		print_r(Util::$trace);
		echo $time;
	}
}
	
echo '<pre>';
UnitTestSuite::run();