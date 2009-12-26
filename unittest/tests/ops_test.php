<?php

$test->addTestCase(new OPSTester);

class OPSTester extends UnitTestCase  
{
	
	public $pirep_id;
	public $report_details;
	
	public function __construct() 
	{
		parent::__construct();
		$this->UnitTestCase('PIREP Testing');
	}
	
	public function testRetrieveAirport()
	{
		echo '<h3>Core API Tests</h3>';
		echo "<strong>Checking geonames server</strong><br />";
		Config::Set('AIRPORT_LOOKUP_SERVER', 'geonames');
		
		OperationsData::RemoveAirport('PANC');
		$return = OperationsData::RetrieveAirportInfo('PANC');
		
		$this->assertNotEqual($return, false);
		
		echo "<strong>Checking phpVMS API server</strong><br />";
		Config::Set('AIRPORT_LOOKUP_SERVER', 'phpvms');
		Config::Set('PHPVMS_API_SERVER', 'http://apidev.phpvms.net');
		OperationsData::RemoveAirport('PANC');
		$return = OperationsData::RetrieveAirportInfo('PANC');
		
		$this->assertNotEqual($return, false);
		
	}
	
	public function testFindSchedules()
	{
		heading('findSchedules');
		$data = SchedulesData::findSchedules(array());
		$this->assertNotEqual($data, false);
		
		
		heading('Find disabled schedules');
		$data = SchedulesData::findSchedules(array('s.enabled'=>0));
		$this->assertNotEqual($data, false);
		
	}
}