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
	
	public function __call($name, $args)
	{
		if($name == 'new')
		{
			$this->filepirep();
		}
	}
	
	public function index()
	{
		$this->viewpireps();
	}
	
	public function mine()
	{
		$this->viewpireps();
	}
	
	public function viewpireps()
	{
		if(!Auth::LoggedIn())
		{
			$this->set('message', 'You are not logged in!');
			$this->render('core_error.tpl');
			return;
		}
		
		if(isset($this->post->submit_pirep) && $this->post->submit_pirep)
		{
			if(!$this->SubmitPIREP())
			{
				$this->FilePIREPForm();
				return false;
			}
		}
		
		// Show PIREPs filed
		
		$this->set('pireps', PIREPData::GetAllReportsForPilot(Auth::$userinfo->pilotid));
		$this->render('pireps_viewall.tpl');
	}
	
	public function view($pirepid='')
	{
		$this->viewreport($pirepid);
	}
	
	public function viewreport($pirepid='')
	{
		if($pirepid == '')
		{
			$this->set('message', 'No report ID specified!');
			$this->render('core_error.tpl');
			return;
		}
		
		$pirep = PIREPData::GetReportDetails($pirepid);
		
		if(!$pirep)
		{
			$this->set('message', 'This PIREP does not exist!');
			$this->render('core_error.tpl');
			return;
		}
		
		$this->set('pirep', $pirep);
		$this->set('fields', PIREPData::GetFieldData($pirepid));
		$this->set('comments', PIREPData::GetComments($pirepid));
										
		$this->render('pirep_viewreport.tpl');
		$this->render('route_map.tpl');
	}
	
	
	public function routesmap()
	{
		$this->title = 'My Flight Map';
		
		$pireps = PIREPData::GetAllReportsForPilot(Auth::$userinfo->pilotid);
		
		if(!$pireps)
		{
			$this->set('message', 'There are no PIREPs for this pilot!!');
			$this->render('core_error.tpl');
			return;
		}
		
		$this->set('allroutes', $pireps);
		$this->render('profile_myroutesmap.tpl');
	}
	
	public function file()
	{
		$this->filepirep();
	}
		
	public function filepirep()
	{
		if(!Auth::LoggedIn())
		{
			$this->set('message', 'You must be logged in to access this feature!');
			$this->render('core_error.tpl');
			return;
		}
		
		$this->FilePIREPForm();
	}
	
	public function getdeptapts($code)
	{
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
		
	}
	
	public function getarrapts($code = '', $icao = '')
	{
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
		
	}
		
	protected function FilePIREPForm()
	{
		$this->set('pilot', Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname);
		$this->set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
		$this->set('pirepfields', PIREPData::GetAllFields());
		$this->set('bid', SchedulesData::GetBid($this->get->id)); // get the bid info
		$this->set('allairports', OperationsData::GetAllAirports());
		$this->set('allairlines', OperationsData::GetAllAirlines(true));
		$this->set('allaircraft', OperationsData::GetAllAircraft(true));
		
		$this->render('pirep_new.tpl');
	}
	
	protected function SubmitPIREP()
	{
		$pilotid = Auth::$userinfo->pilotid;
		
		if($pilotid == '' || Auth::LoggedIn() == false)
		{
			$this->set('message', 'You must be logged in to access this feature!!');
			//$this->render('core_error.tpl');
			return false;
		}		
		
		if($this->post->code == '' || $this->post->flightnum == '' 
				|| $this->post->depicao == '' || $this->post->arricao == '' 
				|| $this->post->aircraft == '' || $this->post->flighttime == '')
		{
			$this->set('message', 'You must fill out all of the required fields!');
			//$this->render('core_error.tpl');
			return false;
		}
		
		$sched_data = SchedulesData::GetScheduleByFlight($this->post->code, $this->post->flightnum);
		if(!$sched_data)
		{
			$this->set('message', 'The flight code and number you entered is not a valid route!');
			//$this->render('core_error.tpl');
			return false;
		}
		
		/* Check the schedule and see if it's been bidded on */
		if(Config::Get('DISABLE_SCHED_ON_BID') == true)
		{
			$biddata = SchedulesData::GetBid($sched_data->bidid);
			
			if($biddata)
			{
				if($biddata->pilotid != $pilotid)
				{
					$this->set('message', 'You are not the bidding pilot');
					//$this->render('core_error.tpl');
					return false;
				}
			}
		}
		
		/*if($this->post->depicao == $this->post->arricao)
		{
			$this->set('message', 'The departure airport is the same as the arrival airport!');
			$this->render('core_error.tpl');
			return false;
		}*/
		
		if(!is_numeric($this->post->flighttime))
		{
			$this->set('message', 'The flight time has to be a number!');
			//$this->render('core_error.tpl');
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
			$this->set('message', 'There was an error adding your PIREP : '.PIREPData::$lasterror);
			//$this->render('core_error.tpl');
			return false;
		}
		
		$pirepid = DB::$insert_id;
		PIREPData::SaveFields($pirepid, $_POST);
		
		# Call the event
		CodonEvent::Dispatch('pirep_filed', 'PIREPS', $_POST);
		
		# Set them as non-retired
		PilotData::setPilotRetired($pilotid, false);
		
		# Send to Central
		CentralData::send_pirep($pirepid);
		
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
	public function RecentFrontPage($count = 10)
	{
		$this->set('reports', PIREPData::GetRecentReportsByCount($count));
		$this->render('frontpage_reports.tpl');
	}
}