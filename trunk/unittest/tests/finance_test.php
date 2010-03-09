<?php
/*	Test registration functionality
 */

$test->addTestCase(new FinanceTester);

class FinanceTester extends UnitTestCase  
{
	public function __construct() 
	{
		parent::__construct();
		$this->UnitTestCase('Finance Testing');
	}
	
	public function testCheckFinances()
	{
		$params = array();
		$all_pireps = PIREPData::findPIREPS($params);
		
		foreach($all_pireps as $pirep)
		{
			$gross1 = floatval($pirep->load) * floatval($pirep->price);
			$gross2 = $pirep->load * $pirep->price;
			
			if($gross1 != $gross2)
			{
				$this->assertEqual(true, false);
			}
		}
	}
}