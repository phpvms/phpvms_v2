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
			case 'editgroup':
			case 'addgroup':
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
						
						Template::Set('message', Lang::gs('pilot.deleted'));
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
						
						if(intval($this->post->retired) == 1)
						{
							$retired = true;
						}
						else
						{
							$retired = false;
						}
						
						PilotData::SaveProfile($this->post->pilotid, $this->post->email , 
													$this->post->location, $this->post->hub, $retired);
													
						PilotData::ReplaceFlightData($this->post->pilotid, $this->post->totalhours, 
														$this->post->totalflights, $this->post->totalpay, $this->post->transferhours);
														
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
				if($this->post->action == 'editgroup')
				{
					# Process
					$this->SaveGroup();
				}
				
				$this->ShowGroups();
				break;
			
			case 'addgroup':
			
				Template::Set('title', 'Add a Group ');
				Template::Set('action', 'addgroup');
				Template::Set('permission_set', Config::Get('permission_set'));
				
				Template::Show('groups_groupform.tpl');
				
				break;
				
			case 'editgroup':
			
				$group_info = PilotGroups::GetGroup($this->get->groupid);
				
				Template::Set('group', $group_info);
				Template::Set('title', 'Editing '.$group_info->name);
				Template::Set('action', 'editgroup');
				Template::Set('permission_set', Config::Get('permission_set'));
				
				Template::Show('groups_groupform.tpl');
			
				break;
				
			case 'pilotawards':
							
				if($this->post->action == 'addaward')
				{			
					$this->AddAward();
				}
				elseif($this->post->action == 'deleteaward')
				{
					$this->DeleteAward();
				}
								
				Template::Set('allawards', AwardsData::GetPilotAwards($_REQUEST['pilotid']));
				Template::Show('pilots_awards.tpl');
				break;
		}
		
	}
	
	public function ShowPilotsList()
	{
		Template::Set('allpilots', PilotData::GetAllPilots(Vars::GET('letter')));
		Template::Show('pilots_list.tpl');
	}
	
	public function ViewPilotDetails()
	{
		//This is for the main tab
		Template::Set('pilotinfo', PilotData::GetPilotData($this->get->pilotid));
		Template::Set('customfields', PilotData::GetFieldData($this->get->pilotid, true));
		Template::Set('allawards', AwardsData::GetPilotAwards($this->get->pilotid));
		Template::Set('pireps', PIREPData::GetAllReportsForPilot($this->get->pilotid));
		Template::Set('countries', Countries::getAllCountries());
		
		$this->SetGroupsData($this->get->pilotid);
		
		Template::Show('pilots_detailtabs.tpl');
	}
	
	public function SetGroupsData($pilotid)
	{
		# This is for the groups tab
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
			Template::Set('message', Lang::gs('group.no.name'));
			Template::Show('core_error.tpl');
			return;
		}
		
		$permissions = 0;
		foreach($this->post->permissions as $perm)
		{
			$permissions = PilotGroups::set_permission($permissions, $perm);
		}
		
		$ret = PilotGroups::AddGroup($name, $permissions);
			
		if(DB::errno() != 0)
		{
			Template::Set('message', sprintf(Lang::gs('error'), DB::$error));
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', sprintf(Lang::gs('group.added'), $this->post->name));
			Template::Show('core_success.tpl');
		}		
	}
	
	public function SaveGroup()
	{		
		$permissions = 0;
		foreach($this->post->permissions as $perm)
		{
			$permissions = PilotGroups::set_permission($permissions, $perm);
		}
		
		PilotGroups::EditGroup($this->post->groupid, $this->post->name, $permissions);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', sprintf(Lang::gs('error'), DB::$error));
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', sprintf(Lang::gs('group.saved'), $this->post->name));
			Template::Show('core_success.tpl');
		}		
	}
	
	
	public function AddPilotToGroup()
	{
		$pilotid = $this->post->pilotid;
		$groupname = $this->post->groupname;
		
		if(PilotGroups::CheckUserInGroup($pilotid, $groupname))
		{
			Template::Set('message', Lang::gs('group.pilot.already.in'));
			Template::Show('core_error.tpl');
			return;
		}
		
		$ret = PilotGroups::AddUsertoGroup($pilotid, $groupname);
		
		if(DB::errno() != 0 )
		{
			Template::Set('message', Lang::gs('group.add.error'));
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', Lang::gs('group.user.added'));
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
	}
	
	public function ApprovePilot()
	{
		PilotData::AcceptPilot($this->post->id);
		RanksData::CalculatePilotRanks();
		
		$pilot = PilotData::GetPilotData($this->post->id);
		
		# Send pilot notification
		
		$subject = Lang::gs('email.register.accepted.subject');
		Template::Set('pilot', $pilot);
		$message = Template::GetTemplate('email_registrationaccepted.tpl', true, true);
	
		Util::SendEmail($pilot->email, $subject, $message);
		
	}
	
	public function RejectPilot()
	{	
		$pilot = PilotData::GetPilotData($this->post->id);
		
		# Send pilot notification
		
		$subject = Lang::gs('email.register.rejected.subject');
				
		Template::Set('pilot', $pilot);		
		$message = "Dear $pilot->firstname $pilot->lastname,
Your registration for ".SITE_NAME." was denied. Please contact an admin at <a href=\"".SITE_URL."\">".SITE_URL."</a> to dispute this. 
				
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
			Template::Set('message', Lang::gs('password.wrong.length'));
			Template::Show('core_message.tpl');
			return;
		}
		
		// Check is passwords are the same
		if($password1 != $password2)
		{
			Template::Set('message', Lang::gs('password.no.match'));
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
			Template::Set('message', Lang::gs('password.changed'));
			Template::Show('core_success.tpl');
		}
	}
	
	protected function AddAward()
	{
		
		if($this->post->awardid == '' || $this->post->pilotid == '')
			return;
					
		# Check if they already have this award
		$award = AwardsData::GetPilotAward($this->post->pilotid, $this->post->awardid);
		if($award)
		{
			Template::Set('message', Lang::gs('award.exists'));
			Template::Show('core_error.tpl');
			return;
		}
		
		AwardsData::AddAwardToPilot($this->post->pilotid, $this->post->awardid);
	}
	
	protected function DeleteAward()
	{
		AwardsData::DeletePilotAward($this->post->id);
		
		if($award)
		{
			Template::Set('message', Lang::gs('award.deleted'));
			Template::Show('core_success.tpl');
			return;
		}
		
	}
}
