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
				$this->set('sidebar', 'sidebar_pirep_pending.tpl');
				break;
		}
	}
	
	public function index()
	{
		$this->viewpending();
	}
	
	public function viewpending()
	{
		if(isset($this->post->action))
		{
			switch($this->post->action)
			{
				case 'addcomment':
					$this->add_comment_post();
					break;
				
				case 'approvepirep':
					$this->approve_pirep_post();
					break;
				
				case 'deletepirep':
					
					$this->delete_pirep_post();
					break;
				
				case 'rejectpirep':
					$this->reject_pirep_post();
					break;
					
				case 'editpirep':
					$this->edit_pirep_post();
					break;
			}
		}
		
		$this->set('title', 'Pending Reports');
		
		if(isset($this->get->hub) && $this->get->hub != '')
		{
			$this->set('pireps', PIREPData::GetAllReportsFromHub(PIREP_PENDING, $this->get->hub));
		}
		else
		{
			$this->set('pireps', PIREPData::GetAllReportsByAccept(PIREP_PENDING));
		}
		
		$this->render('pireps_list.tpl');
	}
	
	
	public function rejectpirep()
	{
		$this->set('pirepid', $this->get->pirepid);
		$this->render('pirep_reject.tpl');
	}
	
	public function viewrecent()
	{
		$this->set('title', Lang::gs('pireps.view.recent'));
		$this->set('pireps', PIREPData::GetRecentReports());
		$this->set('descrip', 'These pilot reports are from the past 48 hours');
		
		$this->render('pireps_list.tpl');
	}
	
	public function approveall()
	{
		echo '<h3>Approve All</h3>';
		
		$allpireps = PIREPData::GetAllReportsByAccept(PIREP_PENDING);
		$total = count($allpireps);
		$count = 0;
		foreach($allpireps as $pirep_details)
		{
			if($pirep_details->aircraft == '')
			{
				continue;
			}
			
			# Update pilot stats
			SchedulesData::IncrementFlownCount($pirep_details->code, $pirep_details->flightnum);
			PIREPData::ChangePIREPStatus($pirep_details->pirepid, PIREP_ACCEPTED); // 1 is accepted
			PilotData::UpdateFlightData($pirep_details->pilotid, $pirep_details->flighttime, 1);
			PilotData::UpdatePilotPay($pirep_details->pilotid, $pirep_details->flighttime);
			
			RanksData::CalculateUpdatePilotRank($pirep_details->pilotid);
			RanksData::CalculatePilotRanks();
			PilotData::GenerateSignature($pirep_details->pilotid);
			StatsData::UpdateTotalHours();
			
			$count++;
		}
		
		$skipped = $total - $count;
		echo "$count of $total were approved ({$skipped} has errors)";
	}
	
	public function viewall()
	{
		if($this->get->start == '')
			$this->get->start = 0;
		
		$num_per_page = 20;
		$allreports = PIREPData::GetAllReports($this->get->start, $num_per_page);
		
		if(count($allreports) >= $num_per_page)
		{
			$this->set('paginate', true);
			$this->set('admin', 'viewall');
			$this->set('start', $this->get->start+20);
		}
		
		$this->set('title', 'PIREPs List');
		$this->set('pireps', $allreports);
		$this->render('pireps_list.tpl');
	}
	
	public function editpirep()
	{
		$this->set('pirep', PIREPData::GetReportDetails($this->get->pirepid));
		$this->set('allairlines', OperationsData::GetAllAirlines());
		$this->set('allairports', OperationsData::GetAllAirports());
		$this->set('allaircraft', OperationsData::GetAllAircraft());
		$this->set('fielddata', PIREPData::GetFieldData($this->get->pirepid));
		$this->set('pirepfields', PIREPData::GetAllFields());
		$this->set('comments', PIREPData::GetComments($this->get->pirepid));
		
		$this->render('pirep_edit.tpl');
	}
	
	public function viewcomments()
	{
		$this->set('comments', PIREPData::GetComments($this->get->pirepid));
		$this->render('pireps_comments.tpl');
	}
	
	public function viewlog()
	{
		$this->set('report', PIREPData::GetReportDetails($this->get->pirepid));
		$this->render('pirep_log.tpl');
		
	}
		
	public function addcomment()
	{
		
		if(isset($this->post->submit))
		{
			$this->add_comment_post();
			
			$this->set('message', 'Comment added to PIREP!');
			$this->render('core_success.tpl');
			return;
		}
		
		$this->set('pirepid', $this->get->pirepid);
		$this->render('pirep_addcomment.tpl');
	}
		
		
	/* Utility functions */
	
	protected function add_comment_post()
	{
		$comment = $this->post->comment;
		$commenter = Auth::$userinfo->pilotid;
		$pirepid = $this->post->pirepid;
	
		$pirep_details = PIREPData::GetReportDetails($pirepid);
		
		PIREPData::AddComment($pirepid, $commenter, $comment);
		
		// Send them an email
		$this->set('firstname', $pirep_details->firstname);
		$this->set('lastname', $pirep_details->lastname);
		$this->set('pirepid', $pirepid);
		
		$message = Template::GetTemplate('email_commentadded.tpl', true);
		Util::SendEmail($pirep_details->email, 'Comment Added', $message);
	}
	
	/**
	 * Approve the PIREP, and then update
	 * the pilot's data
	 */
	protected function approve_pirep_post()
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
		PilotData::UpdateLastPIREPDate($pirep_details->pilotid);
		PilotData::resetPilotPay($pirep_details->pilotid);
	}
	
	/** 
	 * Delete a PIREP
	 */
	 
	protected function delete_pirep_post()
	{
		$pirepid = $this->post->id;
		if($pirepid == '') return;
		
		PIREPData::DeleteFlightReport($pirepid);
	}
		
	
	/**
	 * Reject the report, and then send them the comment
	 * that was entered into the report
	 */
	protected function reject_pirep_post()
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
		PilotData::resetPilotPay($pirep_details->pilotid);
		StatsData::UpdateTotalHours();
		
		// Send comment for rejection
		if($comment != '')
		{
			$commenter = Auth::$userinfo->pilotid; // The person logged in commented
			PIREPData::AddComment($pirepid, $commenter, $comment);
			
			// Send them an email
			$this->set('firstname', $pirep_details->firstname);
			$this->set('lastname', $pirep_details->lastname);
			$this->set('pirepid', $pirepid);
			
			$message = Template::GetTemplate('email_commentadded.tpl', true);
			Util::SendEmail($pirep_details->email, 'Comment Added', $message);
		}
	}
	
	protected function edit_pirep_post()
	{
				
		if($this->post->code == '' || $this->post->flightnum == '' 
			|| $this->post->depicao == '' || $this->post->arricao == '' 
			|| $this->post->aircraft == '' || $this->post->flighttime == '')
		{
			$this->set('message', 'You must fill out all of the required fields!');
			$this->render('core_error.tpl');
			return false;
		}
			
		/*if($this->post->depicao == $this->post->arricao)
		{
			$this->set('message', 'The departure airport is the same as the arrival airport!');
			$this->render('core_error.tpl');
			return false;
		}*/
		
		$this->post->fuelused = str_replace(' ', '', $this->post->fuelused);
		$this->post->fuelused = str_replace(',', '', $this->post->fuelused);
		
		$fuelcost = $this->post->fuelused * $this->post->fuelunitcost;
		
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
					  'price'=>$this->post->price,
					  'pilotpay' => $this->post->pilotpay,
					  'fuelused'=>$this->post->fuelused,
					  'fuelunitcost'=>$this->post->fuelunitcost,
					  'fuelprice'=>$fuelcost,
					  'expenses'=>$this->post->expenses
				);
					 		
		if(!PIREPData::UpdateFlightReport($this->post->pirepid, $data))
		{
			$this->set('message', 'There was an error adding your PIREP');
			$this->render('core_error.tpl');
			return false;
		}
		
		PIREPData::SaveFields($this->post->pirepid, $_POST);
			
		return true;
	}
}