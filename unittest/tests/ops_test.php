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
		Config::Set('AIRPORT_LOOKUP_SERVER', 'geonames');
		
		OperationsData::RemoveAirport('PANC');
		$return = OperationsData::RetrieveAirportInfo('PANC');
		
		$this->assertNotEqual($return, false);
		
		echo '<br />';
		
		Config::Set('AIRPORT_LOOKUP_SERVER', 'phpvms');
		Config::Set('PHPVMS_API_SERVER', 'http://apidev.phpvms.net');
		OperationsData::RemoveAirport('PANC');
		$return = OperationsData::RetrieveAirportInfo('PANC');
		
		$this->assertNotEqual($return, false);
		
		
		echo '<br >';
	}
}