<?php



class PIREPAdmin
{
	
	function Controller()
	{
		// Post actions
		switch(Vars::POST('action'))
		{
			case 'addcomment':
				$this->AddComment();				
				break;
				
			case 'approvepirep':
				$this->ApprovePIREP();
				break;
		}
		
		// Views
		switch(Vars::GET('admin'))
		{
			case 'viewpireps':
			
				Template::Set('pireps', PIREPData::GetAllReportsByAccept(0));
				Template::Set('descrip', 'These PIREPs are pending');
				
				Template::Show('pireps_list.tpl');
				break;
				
			case 'addcomment':
			
				Template::Set('pirepid', Vars::GET('pirepid'));
				Template::Show('pirep_addcomment.tpl');
				break;
		}
	}
	
	function AddComment()
	{
		$comment = Vars::POST('comment');
		$commenter = Auth::$userinfo->pilotid;
		$pirepid = Vars::POST('pirepid');
	
		$pirep_details = PIREPData::GetReportDetails($pirepid);
		
		PIREPData::AddComment($pirepid, $commenter, $comment);
		
		// Send them an email
		Template::Set('firstname', $pirep_details->firstname);
		Template::Set('lastname', $pirep_details->lastname);
		Template::Set('pirepid', $pirepid);
		
		$message = Template::GetTemplate('email_commentadded.tpl', true);
		Util::SendEmail($pirep_details->email, 'Comment Added', $message);			
	}
	
	function ApprovePIREP()
	{
		$pirepid = Vars::POST('id');
		
		if($pirepid == '') return;
			
		$pirep  = PIREPData::GetReportDetails($pirepid);
		
		PIREPData::ChangePIREPStatus($pirepid, '1'); // 1 is accepted
		PilotData::UpdateFlightData(Auth::$userinfo->pilotid, $pirep->flighttime);	
	}
}

?>