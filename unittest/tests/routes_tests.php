<?php

$test->addTestCase(new RouteTester);

class RouteTester extends UnitTestCase  
{
	
	public function testExtractRoute()
	{
		$routes = array(
			'VMA1234A',
			'VMA324AB',
			'VMS3030B'
		);
		
		echo "<strong>Checking to see if flight numbers are parsed</strong><br />";
		# Check the first one
		$route = SchedulesData::getProperFlightNum($routes[0]);
		$this->assertEqual($route['code'], 'VMA');
		$this->assertEqual($route['flightnum'], '1234A');
		
		# Second
		$route = SchedulesData::getProperFlightNum($routes[1]);
		$this->assertEqual($route['code'], 'VMA');
		$this->assertEqual($route['flightnum'], '324AB');
		
		# Third
		$route = SchedulesData::getProperFlightNum($routes[2]);
		$this->assertEqual($route['code'], 'VMS');
		$this->assertEqual($route['flightnum'], '3030B');
		
		echo "<br />";
	}
}