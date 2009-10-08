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
		Template::Set('sidebar', 'sidebar_maintenance.tpl');		
	}
	
	public function Controller()
	{
		
		switch($this->get->page)
		{
			# Show the main menu
			
			case '':
			case 'options':
			
				Template::Show('maintenance_options.tpl');
			
				break;
				
			case 'checkairports':
			
				
			
			
				break;
				
			case 'resetdistances':
			
				echo '<h3>Updating and Calculating Distances</h3>';
				
				# Update all of the schedules
				
				echo '<p><strong>Updating schedules...</strong></p>';
				
				$allschedules = SchedulesData::GetSchedulesNoDistance();
				
				if(!$allschedules)
				{
					echo 'No schedules to update';
					$allschedules = array();
				}
				
				# Check 'em
				foreach($allschedules as $sched)
				{
					$distance = SchedulesData::distanceBetweenPoints($sched->deplat, $sched->deplong, 
																		$sched->arrlat, $sched->arrlong);							
					echo "$sched->code$sched->flightnum - $sched->depname to $sched->arrname "
						."is $distance ".Config::Get('UNIT').'<br />';
						
					SchedulesData::UpdateDistance($sched->id, $distance);
				}
				
				# Update all of the PIREPS
				
				echo '<p><strong>Updating PIREPs...</strong></p>';
				
				$allpireps = PIREPData::GetAllReports('', '');
				
				if(!$allpireps)
				{
					echo 'No PIREPs need updating. Good job!';
					$allpireps = array();
				}
				
				foreach($allpireps as $pirep)
				{
					
					# Find the schedule, and the distance supplied by the schedule:
					
					$sched = SchedulesData::GetScheduleByFlight($pirep->code, $pirep->flightnum);
					
					if(!$sched)
					{
						$distance = SchedulesData::distanceBetweenPoints($pirep->deplat, $pirep->deplong, 
																	 $pirep->arrlat, $pirep->arrlong);	
					}
					else
					{
						$distance = $sched->distance;
					}
													
					echo "PIREP Number $pirep->pirepid ($pirep->code$pirep->flightnum) "
						."$pirep->depname to $pirep->arrname is $distance ".Config::Get('UNIT').'<br />';
					
					$ret = PIREPData::UpdatePIREPDistance($pirep->pirepid, $distance);
					
					if($ret == false)
					{
						echo PIREPData::$lasterror.'<br />';
					}
				}
			
				echo '<p>Completed!</p><br />';
			
				break;
				
			case 'resetsignatures':
					
				$allpilots = PilotData::GetAllPilots();
				
				echo '<h3>Regenerating signatures</h3>
						<strong>Generating signatures...</strong><br />';
				
				foreach($allpilots as $pilot)
				{
					echo "Generating signature for $pilot->firstname $pilot->lastname<br />";
					PilotData::GenerateSignature($pilot->pilotid);
				}
				
				echo "Done";
				
				break;
				
			case 'resethours':
				echo '<h3>Updating Total Hours Count</h3>';
				
				$total = 0;
				echo 'Calculating hours for all pilots: ';
				$allpilots = PilotData::GetAllPilots();
				
				foreach($allpilots as $pilot)
				{
					$hours = PilotData::UpdateFlightHours($pilot->pilotid);
					$total = Util::AddTime($total, $hours);
					echo "Found $hours for number $pilot->pilotid<br />";
				}
				
				echo "Pilots have a total of <strong>$total hours</strong><br /><br />";
				
				echo "<strong>Now counting from PIREPS</strong><br />";
				
				StatsData::UpdateTotalHours();
				echo 'Found '.StatsData::TotalHours().' total hours, updated<br />';
				
				break;
				
			case 'resetpirepfinance':
			
				echo '<h3>Reset PIREP Data</h3> 
						Resetting PIREPs...<br />';
						
				PIREPData::PopulateEmptyPIREPS();
				
				echo 'Complete';
				
				break;
		}
	}
}