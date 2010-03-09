<?php
/*	Test registration functionality
 */

//$test->addTestCase(new PIREPTester);

class PIREPTester extends UnitTestCase  
{
	
	public $pirep_id;
	public $report_details;
	
	public function __construct() 
	{
		parent::__construct();
		$this->UnitTestCase('PIREP Testing');
	}
	
	public function testSubmitPIREP()
	{
		echo '<h3>PIREP Checks</h3>';
		$data = array('pilotid'=>1,
			'code'=>'VMA',
			'flightnum'=>'1352',
			'depicao'=>'KORD',
			'arricao'=>'KJFK',
			'aircraft'=>10,
			'flighttime'=>'4.1',
			'submitdate'=>'NOW()',
			'fuelused'=>'2800',
			'source'=>'unittest',
			'comment'=>'This is a test PIREP');
		
		$info = PIREPData::FileReport($data);
		$this->assertTrue($info, DB::error());
		
		$this->pirep_id = DB::$insert_id;
		
		$this->assertIsA($this->pirep_id, int);
		
		unset($data);
		echo '<br />';
	}
	
	public function testRetrieveReport()
	{
		$this->report_details = PIREPData::GetReportDetails($this->pirep_id);
		$this->assertTrue($this->report_details);
		echo '<br />';
	}
	
	public function testChangePIREPStatus()
	{
		# Reject it first
		$status = PIREPData::ChangePIREPStatus($this->pirep_id, PIREP_REJECTED);
		$this->assertTrue($status, DB::$error);
		
		# Verify status change
		$this->report_details = PIREPData::GetReportDetails($this->pirep_id);
		$this->assertEqual($this->report_details->accepted, PIREP_REJECTED);
		
		# Change to accepted
		$status = PIREPData::ChangePIREPStatus($this->pirep_id, PIREP_ACCEPTED);
		$this->assertTrue($status, DB::$error);
		
		# Verify status change
		$this->report_details = PIREPData::GetReportDetails($this->pirep_id);
		$this->assertEqual($this->report_details->accepted, PIREP_ACCEPTED);
		
		# Verify other changes due to accept
		echo '<br />';
	}
	
	public function testDeletePIREP()
	{
		# Delete it
		PIREPData::DeleteFlightReport($this->pirep_id);
		
		# Verify delete
		$data = PIREPData::GetReportDetails($this->pirep_id);
		$this->assertFalse($data);
		
		echo '<br />';
	}
}
