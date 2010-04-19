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
	public $pirepdata;
	
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
		
		if(isset($this->post->submit))
		{
			
			/* See if the PIREP is valid, and whether it's being edited
				by the owner, not someone else */
				
			$pirep = PIREPData::getReportDetails($this->post->pirepid);
			
			if(!$pirep)
			{
				$this->set('message', 'Invalid PIREP');
				$this->render('core_error.tpl');
				return;
			}
			
			# Make sure pilot ID's match
			if($pirep->pilotid != Auth::$userinfo->pilotid)
			{
				$this->set('message', 'This PIREP is not yours!');
				$this->render('core_error.tpl');
				return;
			}
			
			/* Now do the edit actions */
			
			if($this->post->action == 'addcomment')
			{
				$ret = PIREPData::addComment($this->post->pirepid, Auth::$userinfo->pilotid, $this->post->comment);
				
				$this->set('message', 'Comment added!');
				$this->render('core_success.tpl');
			}
			
			/* Edit the PIREP custom fields */
			elseif($this->post->action == 'editpirep')
			{
				$ret = PIREPData::saveFields($this->post->pirepid, $_POST);
				
				$this->set('message', 'PIREP edited!');
				$this->render('core_success.tpl');
			}
		}
		
		// Show PIREPs filed
		$this->set('userinfo', Auth::$userinfo);
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
		
		$pirep = PIREPData::getReportDetails($pirepid);
		
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
	
	public function addcomment()
	{
		if(!Auth::LoggedIn())
		{
			$this->set('message', 'You must be logged in to access this feature!');
			$this->render('core_error.tpl');
			return;
		}
		
		if(!isset($this->get->id))
		{
			$this->set('message', 'No PIREP specified');
			$this->render('core_error.tpl');
			return;
		}
		
		$pirep = PIREPData::GetReportDetails($this->get->id);
		
		if(!$pirep)
		{
			$this->set('message', 'Invalid PIREP');
			$this->render('core_error.tpl');
			return;
		}
		
		# Make sure pilot ID's match
		if($pirep->pilotid != Auth::$userinfo->pilotid)
		{
			$this->set('message', 'You cannot add a comment to a PIREP that is not yours!');
			$this->render('core_error.tpl');
			return;
		}
		
		# Show the comment form
		$this->set('pirep', $pirep);
		$this->render('pireps_addcomment.tpl');
	}
	
	public function editpirep()
	{
		if(!Auth::LoggedIn())
		{
			$this->set('message', 'You must be logged in to access this feature!');
			$this->render('core_error.tpl');
			return;
		}
		
		if(!isset($this->get->id))
		{
			$this->set('message', 'No PIREP specified');
			$this->render('core_error.tpl');
			return;
		}
		
		$pirep = PIREPData::GetReportDetails($this->get->id);
		if(!$pirep)
		{
			$this->set('message', 'Invalid PIREP');
			$this->render('core_error.tpl');
			return;
		}
		
		# Make sure pilot ID's match
		if($pirep->pilotid != Auth::$userinfo->pilotid)
		{
			$this->set('message', 'You cannot add a comment to a PIREP that is not yours!');
			$this->render('core_error.tpl');
			return;
		}
		
		if(PIREPData::PIREPUnderAge($pirep->pirepid, Config::Get('PIREP_CUSTOM_FIELD_EDIT')) == false)
		{
			$this->set('message', 'You cannot edit a PIREP after the cutoff time of '.Config::Get('PIREP_CUSTOM_FIELD_EDIT').' hours');
			$this->render('core_error.tpl');
			return;
		}
		
		$this->set('pirep', $pirep);
		$this->set('pirepfields', PIREPData::GetAllFields());
		
		$this->render('pirep_editform.tpl');
	}
	
	public function routesmap()
	{
		if(!Auth::LoggedIn())
		{
			$this->set('message', 'You must be logged in to access this feature!');
			$this->render('core_error.tpl');
			return;
		}
		
		$this->title = 'My Flight Map';
		
		$pireps = PIREPData::findPIREPS(array('p.pilotid' => Auth::$userinfo->pilotid));
		
		if(!$pireps)
		{
			$this->set('message', 'There are no PIREPs for this pilot!!');
			$this->render('core_error.tpl');
			return;
		}
		
		$this->set('allschedules', $pireps);
		$this->render('flown_routes_map.tpl');
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
		
		if(isset(CodonRewrite::$peices[2]))
			$id = CodonRewrite::$peices[2];
		else
			$id = '';
			
	
		$this->FilePIREPForm($id);
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
		
	protected function FilePIREPForm($bidid='')
	{
		if(!Auth::LoggedIn())
		{
			$this->set('message', 'You must be logged in to access this feature!');
			$this->render('core_error.tpl');
			return;
		}
		
		$this->set('pilot', Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname);
		$this->set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
		$this->set('pirepfields', PIREPData::GetAllFields());
		
		if($bidid != '')
		{
			$this->set('bid', SchedulesData::GetBid($bidid)); // get the bid info
		}
			
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
			return false;
		}
		
		# Only allow for valid routes to be filed
		$sched_data = SchedulesData::GetScheduleByFlight($this->post->code, $this->post->flightnum);
		if(!$sched_data)
		{
			$this->set('message', 'The flight code and number you entered is not a valid route!');
			return false;
		}
		
		# See if they entered more than 59 in the minutes part of the flight time
		$this->post->flighttime = str_replace(':', '.', $this->post->flighttime);
		$parts = explode('.', $this->post->flighttime);
		if($parts[1] > 59)
		{
			$this->set('message', 'You entered more than 60 minutes in the minutes');
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
		
		/* Removed this check since maybe it's a training flight or something, who knows
		if($this->post->depicao == $this->post->arricao)
		{
			$this->set('message', 'The departure airport is the same as the arrival airport!');
			$this->render('core_error.tpl');
			return false;
		}*/
		
		$this->post->flighttime = str_replace(':', '.', $this->post->flighttime);
		if(!is_numeric($this->post->flighttime))
		{
			$this->set('message', 'The flight time has to be a number!');
			return false;
		}
		
		# form the fields to submit
		$this->pirepdata = array(
			'pilotid'=>$pilotid,
			'code'=>$this->post->code,
			'flightnum'=>$this->post->flightnum,
			'depicao'=>$this->post->depicao,
			'arricao'=>$this->post->arricao,
			'aircraft'=>$this->post->aircraft,
			'flighttime'=>$this->post->flighttime,
			'route' => $this->post->route,
			'submitdate'=>'NOW()',
			'fuelused'=>$this->post->fuelused,
			'source'=>'manual',
			'comment'=>$this->post->comment
		);
	
		CodonEvent::Dispatch('pirep_prefile', 'PIREPS');
		
		if(CodonEvent::hasStop('pirepfile'))
		{
			return false;
		}
				
		if(!PIREPData::FileReport($this->pirepdata))
		{
			$this->set('message', 'There was an error adding your PIREP : '.PIREPData::$lasterror);
			return false;
		}
		
		$pirepid = DB::$insert_id;
		PIREPData::SaveFields($pirepid, $_POST);
		
		# Remove the bid
		$bidid = SchedulesData::GetBidWithRoute($pilotid, $this->post->code, $this->post->flightnum);
		if($bidid)
		{
			SchedulesData::RemoveBid($bidid->bidid);
		}
		
		# Call the event
		CodonEvent::Dispatch('pirep_filed', 'PIREPS');
		
		# Set them as non-retired
		PilotData::setPilotRetired($pilotid, 0);
		
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
		$this->set('reports', PIREPData::getRecentReportsByCount($count));
		$this->render('frontpage_reports.tpl');
	}
}