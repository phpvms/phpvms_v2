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
 
class PIREPS extends CodonModule
{
	public $pirep;
	
	function Controller()
	{
		switch($this->get->page)
		{
			case '':
			case 'mine':
			case 'viewpireps':
			
				if(!Auth::LoggedIn())
				{
					Template::Set('message', 'You are not logged in!');
					Template::Show('core_error.tpl');
					return;
				}
				
				if(isset($_POST['submit_pirep']))
				{
					if(!$this->SubmitPIREP())
					{
						$this->FilePIREPForm();
						return false;
					}
				}
				
				// Show PIREPs filed
							
				Template::Set('pireps', PIREPData::GetAllReportsForPilot(Auth::$userinfo->pilotid));
				Template::Show('pireps_viewall.tpl');
				
				break;
			
			case 'view':
			case 'viewreport':
			
				$pirepid = $this->get->id;
				$pirep = PIREPData::GetReportDetails($pirepid);
				
				if(!$pirep)
				{
					echo '<p>This PIREP does not exist!</p>';
					return;
				}
				
				Template::Set('pirep', $pirep);
				Template::Set('fields', PIREPData::GetFieldData($pirepid));
				Template::Set('comments', PIREPData::GetComments($pirepid));
												
				Template::Show('pirep_viewreport.tpl');
				Template::Show('route_map.tpl');
				break;
			
			/* Show map with all of their routes
			*/
			case 'routesmap':
			
				echo '<h3>All flights</h3>';
				$pireps = PIREPData::GetAllReportsForPilot(Auth::$userinfo->pilotid);
				
				if(!$pireps)
				{
					echo '<p>There are no pilot reports</p>';
					return;
				}
				
				$map = new GoogleMap;
				
				$map->maptype = Config::Get('MAP_TYPE');
				$map->linecolor = Config::Get('MAP_LINE_COLOR');
				
				foreach($pireps as $pirep)
				{
					$map->AddPoint($pirep->deplat, $pirep->deplong, "$pirep->depname ($pirep->depicao)");
					$map->AddPoint($pirep->arrlat, $pirep->arrlong, "$pirep->arrname ($pirep->arricao)");
					$map->AddPolylineFromTo($pirep->deplat, $pirep->deplong, $pirep->arrlat, $pirep->arrlong);
				}
				
				$map->ShowMap(MAP_WIDTH, MAP_HEIGHT);
				
				break;
			
			case 'file':
			case 'new':
			case 'filepirep':
			
				if(!Auth::LoggedIn())
				{
					Template::Set('message', 'You must be logged in to access this feature!');
					Template::Show('core_error.tpl');
					return;
				}
				
				$this->FilePIREPForm();
				
				break;
				
			# These next two are accessed via AJAX
			case 'getdeptapts':
				
				$code = $this->get->id;
				
				if($code=='') return;
				
				$allapts = SchedulesData::GetDepartureAirports($code);
				
				if(!$allapts)
				{
					echo 'There are no routes for this airline<br />';
					return;
				}
				
				echo '<select id="depicao" name="depicao">
						<option value="">Select a Departure Airport';
				
				foreach($allapts as $airport)
				{
					echo '<option value="'.$airport->icao.'">'.$airport->icao . ' - '.$airport->name .'</option>';
				}
				echo '</select>';
				
				break;
				
			case 'getarrapts':
			
				$code = $this->get->id;
				$icao = $this->get->icao;
				
				if($icao == '') return;
				
				$allapts = SchedulesData::GetArrivalAiports($icao, $code);
				
				if(!$allapts)
					return;
					
				echo '<select name="arricao">
						<option value="">Select an Arrival Airport';
				foreach($allapts as $airport)
				{
					echo '<option value="'.$airport->icao.'">'.$airport->icao . ' - '.$airport->name .'</option>';
				}
				echo '</select>';
				
				break;
		}
	}
	
	function FilePIREPForm()
	{
		Template::Set('pilot', Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname);
		Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
		Template::Set('pirepfields', PIREPData::GetAllFields());
		Template::Set('bid', SchedulesData::GetBid($this->get->id)); // get the bid info
		Template::Set('allairports', OperationsData::GetAllAirports());
		Template::Set('allairlines', OperationsData::GetAllAirlines(true));
		Template::Set('allaircraft', OperationsData::GetAllAircraft());
		
		Template::Show('pirep_new.tpl');
	}
	
	function SubmitPIREP()
	{
		$pilotid = Auth::$userinfo->pilotid;
		
		if($pilotid == '' || Auth::LoggedIn() == false)
		{
			Template::Set('message', 'You must be logged in to access this feature!!');
			Template::Show('core_error.tpl');
			return false;
		}		
		
		if($this->post->code == '' || $this->post->flightnum == '' 
				|| $this->post->depicao == '' || $this->post->arricao == '' 
				|| $this->post->aircraft == '' || $this->post->flighttime == '')
		{
			Template::Set('message', 'You must fill out all of the required fields!');
			Template::Show('core_error.tpl');
			return false;
		}
		
		if(!SchedulesData::GetScheduleByFlight($this->post->code, $this->post->flightnum))
		{
			Template::Set('message', 'The flight code and number you entered is not a valid route!');
			Template::Show('core_error.tpl');
			return false;
		}
		
		if($this->post->depicao == $this->post->arricao)
		{
			Template::Set('message', 'The departure airport is the same as the arrival airport!');
			Template::Show('core_error.tpl');
			return false;
		}
		
		if(!is_numeric($this->post->flighttime) && !is_numeric($this->post->flightnum))
		{
			Template::Set('message', 'The flight time has to be a number!');
			Template::Show('core_error.tpl');
			return false;
		}
		
		if(CodonEvent::Dispatch('pirep_prefile', 'PIREPS', $_POST) == false)
		{
			return false;
		}
	
		# form the fields to submit
		$data = array('pilotid'=>$pilotid,
					  'code'=>$this->post->code,
					  'flightnum'=>$this->post->flightnum,
					  'depicao'=>$this->post->depicao,
					  'arricao'=>$this->post->arricao,
					  'aircraft'=>$this->post->aircraft,
					  'flighttime'=>$this->post->flighttime,
					  'submitdate'=>'NOW()',
					  'fuelused'=>$this->post->fuelused,
					  'source'=>'manual',
					  'comment'=>$this->post->comment);
		
		if(!PIREPData::FileReport($data))
		{
			Template::Set('message', 'There was an error adding your PIREP : '.DB::error());
			Template::Show('core_error.tpl');
			return false;
		}
		
		$pirepid = DB::$insert_id;
		PIREPData::SaveFields($pirepid, $_POST);
		
		# Call the event
		CodonEvent::Dispatch('pirep_filed', 'PIREPS', $_POST);
		
		# Delete the bid, if the value for it is set
		if($this->post->bid != '')
		{
			SchedulesData::RemoveBid($this->post->bid);
		}
	
		return true;
	}
	
	/**
	 *
	 */
	function RecentFrontPage($count = 10)
	{
		Template::Set('reports', PIREPData::GetRecentReportsByCount($count));
		
		Template::Show('frontpage_reports.tpl');
	}
}