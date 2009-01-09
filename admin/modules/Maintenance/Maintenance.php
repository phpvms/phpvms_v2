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
				
			case 'resetdistances':
			
				echo '<h3>Updating and Calculating Distances</h3>';
				
				# Update all of the schedules
				
				echo '<p><strong>Updating schedules...</strong></p>';
				
				$allschedules = SchedulesData::GetSchedules();
				
				foreach($allschedules as $sched)
				{
					$distance = SchedulesData::distanceBetweenPoints($sched->deplat, $sched->deplong, 
																		$sched->arrlat, $sched->arrlong);							
					echo "$sched->code$sched->flightnum - $sched->depname to $sched->arrname "
						."is $distance miles<br />";
						
					SchedulesData::UpdateDistance($sched->id, $distance);
				}
				
				# Update all of the PIREPS
				
				echo '<p><strong>Updating PIREPs...</strong></p>';
				
				$allpireps = PIREPData::GetSchedulesNoDistance();
				
				foreach($allpireps as $sched)
				{
					$distance = SchedulesData::distanceBetweenPoints($sched->deplat, $sched->deplong, 
													$sched->arrlat, $sched->arrlong);	
													
					echo "PIREP Number $sched->pirepid ($sched->code$sched->flightnum) "
						."$sched->depname to $sched->arrname is $distance miles<br />";
					
					PIREPData::UpdatePIREPDistance($sched->pirepid, $distance);
					
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
				
			case 'resetpirepfinance':
			
				echo '<h3>Reset PIREP Data</h3> 
						Resetting PIREPs...<br />';
						
				PIREPData::PopulateEmptyPIREPS();
				
				echo 'Complete';
				
				break;
		}
	}
}