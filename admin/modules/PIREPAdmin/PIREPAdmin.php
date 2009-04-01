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
 
class PIREPAdmin extends CodonModule
{
	public function HTMLHead()
	{
		switch($this->get->page)
		{
			case 'viewpending': case 'viewrecent': case 'viewall':
				Template::Set('sidebar', 'sidebar_pirep_pending.tpl');
				break;
		}
	}
	
	public function Controller()
	{
		// Post actions
		switch($this->post->action)
		{
			case 'addcomment':
				$this->AddComment();
				break;
				
			case 'approvepirep':
				$this->ApprovePIREP();
				break;
				
			case 'deletepirep':
				
				$this->DeletePIREP();
				break;
				
			case 'rejectpirep':
				$this->RejectPIREP();
				break;
		}
		
		// Views
		switch($this->get->page)
		{
			case 'rejectpirep':

				Template::Set('pirepid', $this->get->pirepid);
				Template::Show('pirep_reject.tpl');
				
				break;
				
			case 'viewrecent':
				Template::Set('title', 'Recent Reports');
				Template::Set('pireps', PIREPData::GetRecentReports());
				Template::Set('descrip', 'These pilot reports are from the past 48 hours');
				
				Template::Show('pireps_list.tpl');
				
				break;
				
			case '':
			case 'viewpending':
				
				$hub = $this->get->hub;
				
				if($this->post->action == 'editpirep')
				{
					$this->EditPIREP();
				}
				
				Template::Set('title', 'Pending Reports');
				
				if($hub != '')
				{
					Template::Set('pireps', PIREPData::GetAllReportsFromHub(PIREP_PENDING, $hub));
				}
				else
				{
					Template::Set('pireps', PIREPData::GetAllReportsByAccept(PIREP_PENDING));
				}
					
				Template::Show('pireps_list.tpl');
				
				break;
				
			case 'viewall':
				
				if($this->get->start == '')
					$this->get->start = 0;
				
				$num_per_page = 20;
				$allreports = PIREPData::GetAllReports($this->get->start, $num_per_page);
				
				if(count($allreports) >= $num_per_page)
				{
					Template::Set('paginate', true);
					Template::Set('admin', 'viewall');
					Template::Set('start', $this->get->start+20);
				}
				
				Template::Set('title', 'PIREPs List');
				Template::Set('pireps', $allreports);
				Template::Show('pireps_list.tpl');
				
				break;
				
			case 'editpirep':
			
				Template::Set('pirep', PIREPData::GetReportDetails($this->get->pirepid));
				Template::Set('allairlines', OperationsData::GetAllAirlines());
				Template::Set('allairports', OperationsData::GetAllAirports());
				Template::Set('allaircraft', OperationsData::GetAllAircraft());
				Template::Set('fielddata', PIREPData::GetFieldData($this->get->pirepid));
				Template::Set('pirepfields', PIREPData::GetAllFields());
				Template::Set('comments', PIREPData::GetComments($this->get->pirepid));
				
				Template::Show('pirep_edit.tpl');
			
				break;
				
			case 'viewcomments':
				
				Template::Set('comments', PIREPData::GetComments($this->get->pirepid));
				Template::Show('pireps_comments.tpl');
				
				break;
				
			case 'viewlog':
				
				Template::Set('report', PIREPData::GetReportDetails($this->get->pirepid));
				Template::Show('pirep_log.tpl');
				
				break;
				
			case 'addcomment':
				Template::Set('pirepid', $this->get->pirepid);
				
				Template::Show('pirep_addcomment.tpl');
				break;
		}
	}
	
	public function AddComment()
	{
		$comment = $this->post->comment;
		$commenter = Auth::$userinfo->pilotid;
		$pirepid = $this->post->pirepid;
	
		$pirep_details = PIREPData::GetReportDetails($pirepid);
		
		PIREPData::AddComment($pirepid, $commenter, $comment);
		
		// Send them an email
		Template::Set('firstname', $pirep_details->firstname);
		Template::Set('lastname', $pirep_details->lastname);
		Template::Set('pirepid', $pirepid);
		
		$message = Template::GetTemplate('email_commentadded.tpl', true);
		Util::SendEmail($pirep_details->email, 'Comment Added', $message);
		
		
	}
	
	/**
	 * Approve the PIREP, and then update
	 * the pilot's data
	 */
	public function ApprovePIREP()
	{
		$pirepid = $this->post->id;
		
		if($pirepid == '') return;
			
		$pirep_details  = PIREPData::GetReportDetails($pirepid);
		
		if(intval($pirep_details->accepted) == PIREP_ACCEPTED) return;
	
		# Update pilot stats
		SchedulesData::IncrementFlownCount($pirep_details->code, $pirep_details->flightnum);
		PIREPData::ChangePIREPStatus($pirepid, PIREP_ACCEPTED); // 1 is accepted
		PilotData::UpdateFlightData($pirep_details->pilotid, $pirep_details->flighttime, 1);
		PilotData::UpdatePilotPay($pirep_details->pilotid, $pirep_details->flighttime);
			
		RanksData::CalculateUpdatePilotRank($pirep_details->pilotid);
		PilotData::GenerateSignature($pirep_details->pilotid);
		StatsData::UpdateTotalHours();
	}
	
	/** 
	 * Delete a PIREP
	 */
	 
	public function DeletePIREP()
	{
		$pirepid = $this->post->id;
		if($pirepid == '') return;
		
		PIREPData::DeleteFlightReport($pirepid);
	}
		
	
	/**
	 * Reject the report, and then send them the comment
	 * that was entered into the report
	 */
	public function RejectPIREP()
	{
		$pirepid = $this->post->pirepid;
		$comment = $this->post->comment;
				
		if($pirepid == '' || $comment == '') return;
	
		PIREPData::ChangePIREPStatus($pirepid, PIREP_REJECTED); // 2 is rejected
		$pirep_details = PIREPData::GetReportDetails($pirepid);
		
		// If it was previously accepted, subtract the flight data
		if(intval($pirep_details->accepted) == PIREP_ACCEPTED)
		{
			PilotData::UpdateFlightData($pirep_details->pilotid, -1 * floatval($pirep->flighttime), -1);
		}

		RanksData::CalculateUpdatePilotRank($pirep_details->pilotid);
		StatsData::UpdateTotalHours();
		
		// Send comment for rejection
		if($comment != '')
		{
			$commenter = Auth::$userinfo->pilotid; // The person logged in commented
			PIREPData::AddComment($pirepid, $commenter, $comment);
			
			// Send them an email
			Template::Set('firstname', $pirep_details->firstname);
			Template::Set('lastname', $pirep_details->lastname);
			Template::Set('pirepid', $pirepid);
			
			$message = Template::GetTemplate('email_commentadded.tpl', true);
			Util::SendEmail($pirep_details->email, 'Comment Added', $message);
		}
	}
	
	public function EditPIREP()
	{
				
		if($this->post->code == '' || $this->post->flightnum == '' 
			|| $this->post->depicao == '' || $this->post->arricao == '' 
			|| $this->post->aircraft == '' || $this->post->flighttime == '')
		{
			Template::Set('message', 'You must fill out all of the required fields!');
			Template::Show('core_error.tpl');
			return false;
		}
			
		if($this->post->depicao == $this->post->arricao)
		{
			Template::Set('message', 'The departure airport is the same as the arrival airport!');
			Template::Show('core_error.tpl');
			return false;
		}		
		
		$fuelused = str_replace(' ', '', $this->post->fuelused);
		$fuelused = str_replace(',', '', $fuelused);
		
		# form the fields to submit
		$data = array('pirepid'=>$this->post->pirepid,
					  'code'=>$this->post->code,
					  'flightnum'=>$this->post->flightnum,
					  'leg'=>$this->post->leg,
					  'depicao'=>$this->post->depicao,
					  'arricao'=>$this->post->arricao,
					  'aircraft'=>$this->post->aircraft,
					  'flighttime'=>$this->post->flighttime,
					  'load'=>$this->post->load,
					  'fuelused'=>$fuelused,
					  'fuelprice'=>$this->post->fuelprice);
		
		if(!PIREPData::UpdateFlightReport($this->post->pirepid, $data))
		{
			Template::Set('message', 'There was an error adding your PIREP');
			Template::Show('core_error.tpl');
			return false;
		}
		
		PIREPData::SaveFields($this->post->pirepid, $_POST);
			
		return true;
	}
}