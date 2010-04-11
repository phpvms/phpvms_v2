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
		switch($this->controller->function)
		{
			case '':
			default:
			case 'processimport':
				
				$this->set('sidebar', 'sidebar_import.tpl');
				
				break;
		}
	}
	
	public function index()
	{
		$this->render('import_form.tpl');
	}
	
	public function export()
	{
		$this->render('export_form.tpl');
	}
	
	public function processexport()
	{
		$export='';
		$all_schedules = SchedulesData::GetSchedules('', false);
		
		if(!$all_schedules)
		{
			echo 'No schedules found!';
			return;
		}
		
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="schedules.csv"');
		
		$fp = fopen('php://output', 'w');
		
		$line=file_get_contents(SITE_ROOT.'/admin/lib/template.csv');
		fputcsv($fp, explode(',', $line));
		
		foreach($all_schedules as $s)
		{
			$line ="{$s->code},{$s->flightnum},{$s->depicao},{$s->arricao},"
					."{$s->route},{$s->registration},{$s->flightlevel},{$s->distance},"
					."{$s->deptime}, {$s->arrtime}, {$s->flighttime}, {$s->notes}, "
					."{$s->price}, {$s->flighttype}, {$s->daysofweek}, {$s->enabled}";
					
			fputcsv($fp, explode(',', $line));
		}
	
		fclose($fp);
	}
	
	public function processimport()
	{
		echo '<h3>Processing Import</h3>';
		
		if(!file_exists($_FILES['uploadedfile']['tmp_name']))
		{
			$this->set('message', 'File upload failed!');
			$this->render('core_error.tpl');
			return;
		}
		
		echo '<p><strong>DO NOT REFRESH OR STOP THIS PAGE</strong></p>';
		
		set_time_limit(270);
		$errs = array();
		$skip = false;
		
		$fp = fopen($_FILES['uploadedfile']['tmp_name'], 'r');
		
		if(isset($_POST['header'])) $skip = true;
		
		/* Delete all schedules before doing an import */
		if(isset($_POST['erase_routes']))
		{
			SchedulesData::deleteAllSchedules();
		}
		
		
		$added = 0;
		$updated = 0;
		$total = 0;
		echo '<div style="overflow: auto; height: 400px; border: 1px solid #666; margin-bottom: 20px; padding: 5px; padding-top: 0px; padding-bottom: 20px;">';
		
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
			$flightlevel = $fields[6];
			$distance = $fields[7];
			$deptime = $fields[8];
			$arrtime = $fields[9];
			$flighttime = $fields[10];
			$notes = $fields[11];
			$price = $fields[12];
			$flighttype = $fields[13];
			$daysofweek = $fields[14];
			$enabled = $fields[15];
							
			if($code == '')
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
				$this->get_airport_info($depicao);
			}
			
			if(!($arrapt = OperationsData::GetAirportInfo($arricao)))
			{
				$this->get_airport_info($arricao);			
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
			
			if($enabled == '0')
				$enabled = false;
			else
				$enabled = true;
			
			# This is our 'struct' we're passing into the schedule function
			#	to add or edit it
			
			$data = array(	'code'=>$code,
							'flightnum'=>$flightnum,
							'depicao'=>$depicao,
							'arricao'=>$arricao,
							'route'=>$route,
							'aircraft'=>$ac,
							'flightlevel'=>$flightlevel,
							'distance'=>$distance,
							'deptime'=>$deptime,
							'arrtime'=>$arrtime,
							'flighttime'=>$flighttime,
							'daysofweek'=>$daysofweek,
							'notes'=>$notes,
							'enabled'=>$enabled,
							'price'=>$price,
							'flighttype'=>$flighttype);
				
			# Check if the schedule exists:
			if(($schedinfo = SchedulesData::getScheduleByFlight($code, $flightnum)))
			{
				# Update the schedule instead
				$val = SchedulesData::updateScheduleFields($schedinfo->id, $data);
				$updated++;
			}
			else
			{
				# Add it
				$val = SchedulesData::addSchedule($data);
				$added++;
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
					echo "$code$flightnum was not added, reason: $error<br />";
				}
				
				echo '<br />';
			}
			else
			{
				$total++;
				echo "Imported {$code}{$flightnum} ({$depicao} to {$arricao})<br />";
			}
		}
		
		CentralData::send_schedules();
		
		echo "The import process is complete, added {$added} schedules, updated {$updated}, for a total of {$total}<br />";
		
		foreach($errs as $error)
		{
			echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$error.'<br />';
		}
		
		echo '</div>';
	}
	
	protected function get_airport_info($icao)
	{
		echo "ICAO $icao not added... retriving information: <br />";						
		$aptinfo = OperationsData::RetrieveAirportInfo($icao);
		
		if($aptinfo === false)
		{
			echo 'Could not retrieve information for '.$icao.', add it manually <br />';
		}
		else
		{
			echo "Found: $icao - ".$aptinfo->name
				.' ('.$aptinfo->lat.','.$aptinfo->lng.'), airport added<br /><br />';
				
			return $aptinfo;
		}
	}
}