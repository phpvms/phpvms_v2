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
	function HTMLHead()
	{
		switch($this->get->admin)
		{
			case 'viewpending': case 'viewrecent': case 'viewall':
				Template::Set('sidebar', 'sidebar_pirep_pending.tpl');
				break;
		}
	}
	
	function Controller()
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
				
			case 'rejectpirep':
				$this->RejectPIREP();
				break;
		}
		
		// Views
		switch($this->get->admin)
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
				
			case 'viewpending':
				
				$hub = $this->get->hub;
				
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
				
			case 'viewcomments':
				
				Template::Set('comments', PIREPData::GetComments($this->get->pirepid));
				Template::Show('pireps_comments.tpl');
				
				break;
				
			case 'addcomment':
				Template::Set('pirepid', $this->get->pirepid);
				
				Template::Show('pirep_addcomment.tpl');
				break;
		}
	}
	
	function AddComment()
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
	function ApprovePIREP()
	{
		$pirepid = $this->post->id;
		
		if($pirepid == '') return;
			
		$pirep_details  = PIREPData::GetReportDetails($pirepid);
		
		if(intval($pirep_details->accepted) == PIREP_ACCEPTED) return;
	
		PIREPData::ChangePIREPStatus($pirepid, PIREP_ACCEPTED); // 1 is accepted
		PilotData::UpdateFlightData($pirep_details->pilotid, $pirep_details->flighttime, 1);
		//DB::debug();
		//RanksData::CalculatePilotRanks();
		
		PilotData::UpdatePilotPay($pirep_details->pilotid, $pirep_details->flighttime);
		
		RanksData::CalculateUpdatePilotRank($pirep_details->pilotid);
	}
	
	/**
	 * Reject the report, and then send them the comment
	 * that was entered into the report
	 */
	function RejectPIREP()
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
		
		//RanksData::CalculatePilotRanks();
		RanksData::CalculateUpdatePilotRank($pirep_details->pilotid);
		
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
}

?>