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

class Import extends CodonModule
{
	function HTMLHead()
	{		
		switch($this->get->page)
		{
			case '':
			default:
			case 'processimport':
				
				Template::Set('sidebar', 'sidebar_import.tpl');
				
				break;
		}
	}
	
	function Controller()
	{
		
		switch($this->get->page)
		{
			default:
			case '':
				
				Template::Show('import_form.tpl');
				
				break;
				
			case 'processimport':
				
				echo '<h3>Processing Import</h3>';
				
				if(!file_exists($_FILES['uploadedfile']['tmp_name']))
				{
					Template::Set('message', 'File upload failed!');
					Template::Show('core_error.tpl');
					return;
				}
				
				echo '<p><strong>DO NOT REFRESH OR STOP THIS PAGE</strong></p>';
				
				set_time_limit(270);
				$errs = array();
				$skip = false;
				
				$fp = fopen($_FILES['uploadedfile']['tmp_name'], 'r');
				
				if(isset($_POST['header'])) $skip = true;
				
				while($fields = fgetcsv($fp, 1000, ','))
				{
					// Skip the first line
					if($skip == true)
					{
						$skip = false;
						continue;
					}
					
					// list fields:
					$code = $fields[0];
					$flightnum = $fields[1];
					$depicao = $fields[2];
					$arricao = $fields[3];
					$route = $fields[4];
					$aircraft = $fields[5];
					$distance = $fields[6];
					$deptime = $fields[7];
					$arrtime = $fields[8];
					$flighttime = $fields[9];
					$notes = $fields[10];
					$price = $fields[11];
					$flighttype = $fields[12];
					$daysofweek = $fields[13];
									
					if($code=='')
					{
						continue;
					}
					
					// Check the code:
					if(!OperationsData::GetAirlineByCode($code))
					{
						echo "Airline with code $code does not exist! Skipping...<br />";
						continue;
					}
					
					// Make sure airports exist:
					if(!($depapt = OperationsData::GetAirportInfo($depicao)))
					{
						echo "ICAO $depicao not added... retriving information: <br />";						
						$aptinfo = OperationsData::RetrieveAirportInfo($depicao);
						
						echo "Found: $depicao - ".$aptinfo->name
							.' ('.$aptinfo->lat.','.$aptinfo->lng.'), airport added<br /><br />';
					}
					
					if(!($arrapt = OperationsData::GetAirportInfo($arricao)))
					{
						echo "ICAO $arricao not added... retriving information: <br />";
						$aptinfo = OperationsData::RetrieveAirportInfo($arricao);						
					}
					
					# Check the aircraft
					$aircraft = trim($aircraft);
					$ac_info = OperationsData::GetAircraftByReg($aircraft);
					
					# If the aircraft doesn't exist, skip it
					if(!$ac_info)
					{
						echo 'Aircraft "'.$aircraft.'" does not exist! Skipping<br />';
						continue;
					}
					$ac = $ac_info->id;
					
					if($flighttype == '')
					{
						$flighttype = 'P';
					}
					
					if($daysofweek == '')
						$daysofweek = '0123456';
					
					
					# Check the distance
					
					if($distance == 0 || $distance == '')
					{
						$distance = OperationsData::getAirportDistance($depicao, $arricao);
					}
					
					$flighttype = strtoupper($flighttype);
					
					# This is our 'struct' we're passing into the schedule function
					#	to add or edit it
					
					$data = array(	'scheduleid'=>$schedinfo->id,
									'code'=>$code,
									'flightnum'=>$flightnum,
									'leg'=>$leg,
									'depicao'=>$depicao,
									'arricao'=>$arricao,
									'route'=>$route,
									'aircraft'=>$ac,
									'distance'=>$distance,
									'deptime'=>$deptime,
									'arrtime'=>$arrtime,
									'flighttime'=>$flighttime,
									'daysofweek'=>$daysofweek,
									'notes'=>$notes,
									'enabled'=>true,
									'maxload'=>$maxload,
									'price'=>$price,
									'flighttype'=>$flighttype);
						
					# Check if the schedule exists:
					if(($schedinfo = SchedulesData::GetScheduleByFlight($code, $flightnum)))
					{
						# Update the schedule instead
						$val = SchedulesData::EditSchedule($data);
					
					}
					else
					{
						# Add it
					
						/*$val = SchedulesData::AddSchedule($code, $flightnum, $leg, $depicao, $arricao,
										$route, $ac, $distance, $deptime, $arrtime, $flighttime, $notes);*/
						$val = SchedulesData::AddSchedule($data);
										
					}
					
					if($val === false)
					{
						if(DB::errno() == 1216)
						{
							echo "Error adding $code$flightnum: The airline code, airports, or aircraft does not exist";
						}
						else
						{
							$error = (DB::error() != '') ? DB::error() : 'Route already exists';
							echo "$code$flightnum was not added, reason: $error";
						}
						
						echo '<br />';
					}
					else
					{
						echo "Imported $code$flightnum ($depicao to $arricao)<br />";
					}
				}
				
				CentralData::send_schedules();
				
				echo 'The import process is complete!<br />';
				
				foreach($errs as $error)
				{
					echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$error.'<br />';
				}
				
				break;
		}
	}
}
?>