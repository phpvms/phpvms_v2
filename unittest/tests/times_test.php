<?php


class TimesTester extends UnitTestCase 
{
	
	public $added_time = '';
	public function __construct()
	{
		parent::__construct();
		//$this->UnitTestCase('Checking PIREP Time Additions');
	}
	
	public function testTimesAdded()
	{
		$sql='SELECT `flighttime`
			  FROM '.TABLE_PREFIX.'pireps 
			  WHERE `accepted`='.PIREP_ACCEPTED;
			  
		$results = DB::get_results($sql);
		$this->added_time = 0;
		foreach($results as $row)
		{
			$this->added_time = Util::AddTime($this->added_time, $row->flighttime);
		}

		$this->assertNotEqual(0, $this->added_time);
		
		// Now calculate by PIREP
		$allpilots = PilotData::GetAllPilots();
		
		$total = 0;
		foreach($allpilots as $pilot)
		{
			$p_hours = PilotData::getPilotHours($pilot->pilotid);
			$total = Util::AddTime($total, $p_hours);
		}
		
		$this->assertNotEqual(0, $total);
		$this->assertEqual($total, $this->added_time);		
	}
}