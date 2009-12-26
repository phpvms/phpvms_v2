<?php

$test->addTestCase(new TimesTester);

class TimesTester extends UnitTestCase 
{
	public $added_time = '';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function testTimesAdded()
	{
		echo '<h3>Checking Times</h3>';
		$sql='SELECT `flighttime`
			  FROM '.TABLE_PREFIX.'pireps 
			  WHERE `accepted`='.PIREP_ACCEPTED;
			  
		$results = DB::get_results($sql);
		$this->added_time = 0;
		foreach($results as $row)
		{
			$this->added_time = Util::AddTime($this->added_time, $row->flighttime);
		}

		heading('Time added, all PIREPS at once');
		$this->assertNotEqual(0, $this->added_time);
		
		heading('Time added, pilot by pilot');
		// Now calculate by PIREP
		$allpilots = PilotData::GetAllPilots();
		
		$total = 0;
		foreach($allpilots as $pilot)
		{
			$p_hours = PilotData::getPilotHours($pilot->pilotid);
			$total = Util::AddTime($total, $p_hours);
		}
		
		$this->assertNotEqual(0, $total);
		
		heading('Comparing pilot to pilot vs all PIREPS');
		$this->assertEqual($total, $this->added_time);
		
		heading('Compare to STAT total hours');
		$this->assertEqual($total, StatsData::TotalHours());
		
		
		echo '<br />';
	}
}