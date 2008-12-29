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
 * @package module_admin_pilots
 */
 
class PilotAdmin extends CodonModule
{

	public function HTMLHead()
	{
		switch($this->get->page)
		{
			case 'viewpilots':
				Template::Set('sidebar', 'sidebar_pilots.tpl');
				break;
			case 'pendingpilots':
				Template::Set('sidebar', 'sidebar_pending.tpl');
				break;
			case 'pilotgroups':
				Template::Set('sidebar', 'sidebar_groups.tpl');
				break;
		}
	}
		
	public function Controller()
	{

		switch($this->get->page)
		{
			case 'viewpilots':

				/* This function is called for *ANYTHING* in that popup box
					
					Preset all of the template items in this function and
					call them in the subsequent templates
					
					Confusing at first, but easier than loading each tab
					independently via AJAX. Though may be an option later
					on, but can certainly be done by a plugin (Add another
					tab through AJAX). The hook is available for whoever
					wants to use it
				*/
				switch($this->post->action)
				{
					case 'changepassword':
				
						$this->ChangePassword();
						return;
				
						break;
						
					case 'deletepilot':
						$pilotid = $this->post->pilotid;
						
						PilotData::DeletePilot($pilotid);
						
						Template::Set('message', 'Pilot has been deleted!');
						Template::Show('core_success.tpl');
						
						break;
				/* These are reloaded into the #pilotgroups ID
					so the entire groups list is refreshed
					*/
					case 'addgroup':
				
						$this->AddPilotToGroup();
						$this->SetGroupsData($this->post->pilotid);
						Template::Show('pilots_groups.tpl');
						return;
						
						break;
				
					case 'removegroup':
				
						$this->RemovePilotGroup();
						
						$this->SetGroupsData($this->post->pilotid);
						Template::Show('pilots_groups.tpl');
						return;
						break;
						
					case 'saveprofile':
						
						# Save their profile
						PilotData::ChangeName($this->post->pilotid, $this->post->firstname, $this->post->lastname);
						
						PilotData::SaveProfile($this->post->pilotid, $this->post->email , 
													$this->post->location, $this->post->hub);
													
						PilotData::ReplaceFlightData($this->post->pilotid, $this->post->totalhours, 
														$this->post->totalflights, $this->post->totalpay);
														
						PilotData::SaveFields($this->post->pilotid, $_POST);
						
						RanksData::CalculateUpdatePilotRank($this->post->pilotid);
						PilotData::GenerateSignature($this->post->pilotid);
						
						Template::Set('message', 'Profile updated successfully');
						Template::Show('core_success.tpl');
						
						return;
						break;
				}
				
				
				if($this->get->action == 'viewoptions')
				{
					$this->ViewPilotDetails();
					return;
				}
				
				$this->ShowPilotsList();
				break;

			case 'pendingpilots':

                switch($this->post->action)
                {
					case 'approvepilot':
						
						$this->ApprovePilot();
						
						break;
					case 'rejectpilot':
						
						$this->RejectPilot();
						
						break;
				}

				Template::Set('allpilots', PilotData::GetPendingPilots());
				Template::Show('pilots_pending.tpl');
				break;
			
			case 'pilotgroups':
			
				if($this->post->action == 'addgroup')
				{
					$this->AddGroup();
				}
				
				$this->ShowGroups();
				break;
		}
		
	}
	
	public function ShowPilotsList()
	{
		$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
						 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
						 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		
		Template::Set('allletters', $letters);
		Template::Set('allpilots', PilotData::GetAllPilots(Vars::GET('letter')));
		
		Template::Show('pilots_list.tpl');
	}
	
	public function ViewPilotDetails()
	{
		$pilotid = $this->get->pilotid;
		
		//This is for the main tab
		Template::Set('pilotinfo', PilotData::GetPilotData($pilotid));
		Template::Set('customfields', PilotData::GetFieldData($pilotid, true));
		Template::Set('pireps', PIREPData::GetAllReportsForPilot($pilotid));
		Template::Set('countries', Countries::getAllCountries());
		
		$this->SetGroupsData($pilotid);
		
		Template::Show('pilots_detailtabs.tpl');
	}
	
	public function SetGroupsData($pilotid)
	{
		//This is for the groups tab
		// Only send the groups they're in
		$freegroups = array();
		
		$allgroups = PilotGroups::GetAllGroups();
		foreach($allgroups as $group)
		{
			if(!PilotGroups::CheckUserInGroup($pilotid, $group->groupid))
			{
				array_push($freegroups, $group->name);
			}
		}
		
		Template::Set('pilotid', $pilotid);
		Template::Set('pilotgroups', PilotData::GetPilotGroups($pilotid));
		Template::Set('freegroups', $freegroups);
	}
	
	public function AddGroup()
	{
		$name = $this->post->name;
		
		if($name == '')
		{
			Template::Set('message', 'You must enter a name!');
			Template::Show('core_error.tpl');
			return;
		}
		
			$ret = PilotGroups::AddGroup($name);
			
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error!');
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'The group "'.$name.'" has been added');
			Template::Show('core_success.tpl');
		}		
	}
	
	public function AddPilotToGroup()
	{
		$pilotid = $this->post->pilotid;
		$groupname = $this->post->groupname;
		
		if(PilotGroups::CheckUserInGroup($pilotid, $groupname))
		{
			Template::Set('message', 'This user is already in this group!');
			Template::Show('core_error.tpl');
			return;
		}
		
		$ret = PilotGroups::AddUsertoGroup($pilotid, $groupname);
		
		if(DB::errno() != 0 )
		{
			Template::Set('message', 'There was an error adding this user');
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'User has been added to the group!');
			Template::Show('core_success.tpl');
		}		
	}
	
	public function RemovePilotGroup()
	{
		$pilotid = $this->post->pilotid;
		$groupid = $this->post->groupid;
		
		PilotGroups::RemoveUserFromGroup($pilotid, $groupid);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error removing');
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'Removed');
			Template::Show('core_success.tpl');
		}
	}
	
	public function ShowGroups()
	{
		Template::Set('allgroups', PilotGroups::GetAllGroups());
		Template::Show('groups_grouplist.tpl');
		Template::Show('groups_addgroup.tpl');
	}
	
	public function ApprovePilot()
	{
		PilotData::AcceptPilot($this->post->id);
		RanksData::CalculatePilotRanks();
		
		$pilot = PilotData::GetPilotData($this->post->id);
		
		# Send pilot notification
		
		$subject = 'Your registration was accepted - '.SITE_NAME;
		$message = "Dear $pilot->firstname $pilot->lastname,
Your registration for ".SITE_NAME." was accepted! Please visit us 
at <a href=\"".SITE_URL."\">".SITE_URL."</a> to login and complete your registration

Thanks!
".SITE_NAME." Staff";
	
		Util::SendEmail($pilot->email, $subject, $message);
		
	}
	
	public function RejectPilot()
	{	
		$pilot = PilotData::GetPilotData($this->post->id);
		
		# Send pilot notification
		
		$subject = 'Your registration was accepted - '.SITE_NAME;
		$message = "Dear $pilot->firstname $pilot->lastname,
				Your registration for ".SITE_NAME." was accepted! Please visit us 
				at <a href=\"".SITE_URL."\">".SITE_URL."</a> to login and complete your registration
				
				Thanks!
				".SITE_NAME." Staff";
		
		Util::SendEmail($pilot->email, $subject, $message);
		
		# Reject in the end, since it's delted
		PilotData::RejectPilot($this->post->id);
	}
	
	public function ChangePassword()
	{
		$password1 = $this->post->password1;
		$password2 = $this->post->password2;
		
		// Check password length
		if(strlen($password1) <= 5)
		{
			Template::Set('message', 'Password is less than 5 characters');
			Template::Show('core_message.tpl');
			return;
		}
		
		// Check is passwords are the same
		if($password1 != $password2)
		{
			Template::Set('message', 'The passwords do not match');
			Template::Show('core_message.tpl');
			return;
		}
		
		RegistrationData::ChangePassword($this->post->pilotid, $password1);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error, administrator has been notified');
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'Password has been successfully changed');
			Template::Show('core_success.tpl');
		}
	}
}
?>