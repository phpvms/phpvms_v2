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

class Maintenance extends CodonModule
{

	public function HTMLHead()
	{
		$this->set('sidebar', 'sidebar_maintenance.tpl');		
	}
	
	public function index()
	{
		$this->options();
	}
	
	public function options()
	{
		$this->render('maintenance_options.tpl');
	}
	
	public function resetdistances()
	{
		echo '<h3>Updating and Calculating Distances</h3>';
		
		# Update all of the schedules
		echo '<p><strong>Updating schedules...</strong></p>';
		
		//$allschedules = SchedulesData::GetSchedulesNoDistance();
		$allschedules = SchedulesData::findSchedules(array('s.distance' => 0));
		
		if(!$allschedules)
		{
			echo 'No schedules to update';
			$allschedules = array();
		}
		
		# Check 'em
		foreach($allschedules as $sched)
		{
			$distance = SchedulesData::distanceBetweenPoints($sched->deplat, $sched->deplng, 
																$sched->arrlat, $sched->arrlng);	
																
			$distance = sprintf("%.2f", $distance);						
			echo "$sched->code$sched->flightnum - $sched->depname to $sched->arrname "
				."is $distance ".Config::Get('UNIT').'<br />';
				
			SchedulesData::updateScheduleFields($sched->id, array('distance' => $distance));
		}
		
		# Update all of the PIREPS
		
		echo '<p><strong>Updating PIREPs...</strong></p>';
		
		$allpireps = PIREPData::findPIREPS(array());
		
		if(!$allpireps)
		{
			echo 'No PIREPs need updating!';
			$allpireps = array();
		}
		
		foreach($allpireps as $pirep)
		{
			
			# Find the schedule, and the distance supplied by the schedule:
			$distance = SchedulesData::distanceBetweenPoints($pirep->deplat, $pirep->deplong, 
				$pirep->arrlat, $pirep->arrlong);	
			
			$distance = sprintf("%.2f", $distance);
											
			echo "PIREP Number $pirep->pirepid ($pirep->code$pirep->flightnum) "
				."$pirep->depname to $pirep->arrname is $distance ".Config::Get('UNIT').'<br />';
			
			PIREPData::editPIREPFields($pirep->pirepid, array('distance'=>$distance));
		}
	
		echo '<p>Completed!</p><br />';
		
		LogData::addLog(Auth::$userinfo->pilotid, 'Reset distances');
	}
	
	public function resetacars()
	{
		echo '<h3>ACARS Reset</h3>';
		
		ACARSData::resetFlights();
		
	}
	
	public function calculateranks()
	{
		echo '<h3>Resetting Ranks</h3>';
		RanksData::CalculatePilotRanks();
		echo 'Done!';
		
		LogData::addLog(Auth::$userinfo->pilotid, 'Recalculated ranks');
	}
	
	public function resetpilotpay()
	{
		echo '<h3>Resetting Pilot Pay</h3>';
		$allpilots = PilotData::GetAllPilots();
		
		foreach($allpilots as $p)
		{
			$total = PilotData::resetPilotPay($p->pilotid);
			
			echo "{$p->firstname} {$p->lastname} - total $ {$total}<br />";
		}
		
		echo 'Done';
		
		LogData::addLog(Auth::$userinfo->pilotid, 'Reset pilot pay');
	}
	
	
	public function resetsignatures()
	{
		$allpilots = PilotData::GetAllPilots();
				
		echo '<h3>Regenerating signatures</h3>
				<strong>Generating signatures...</strong><br />';
		
		foreach($allpilots as $pilot)
		{
			echo "Generating signature for $pilot->firstname $pilot->lastname<br />";
			PilotData::GenerateSignature($pilot->pilotid);
		}
		
		echo "Done";
		
		LogData::addLog(Auth::$userinfo->pilotid, 'Reset signatures');
	}
	
	public function resethours()
	{
		echo '<h3>Updating Total Hours Count</h3>';
				
		$total = 0;
		echo '<p>Calculating hours for all pilots: <br />';
		$allpilots = PilotData::GetAllPilots();
		
		foreach($allpilots as $pilot)
		{
			$hours = PilotData::UpdateFlightHours($pilot->pilotid);
			$total = Util::AddTime($total, $hours);
			echo PilotData::GetPilotCode($pilot->code, $pilot->pilotid) . " - found {$hours} flight hours for number <br />";
		}
		
		echo "Pilots have a total of <strong>$total hours</strong><br /><br />";
		
		echo "<strong>Now counting from PIREPS</strong><br />";
		
		StatsData::UpdateTotalHours();
		echo 'Found '.StatsData::TotalHours().' total hours, updated<br /></p>';
		
		
		LogData::addLog(Auth::$userinfo->pilotid, 'Reset hours');
	}
	
	public function resetpirepfinance()
	{
		echo '<h3>Reset PIREP Data</h3> 
				Resetting PIREPs...<br />';
				
		PIREPData::PopulateEmptyPIREPS();
		
		echo 'Complete';
		
		LogData::addLog(Auth::$userinfo->pilotid, 'Reset PIREP finances');
	}
}