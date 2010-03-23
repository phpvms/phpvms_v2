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
				$this->set('sidebar', 'sidebar_pilots.tpl');
				break;
			case 'pendingpilots':
				$this->set('sidebar', 'sidebar_pending.tpl');
				break;
			case 'pilotgroups':
			case 'editgroup':
			case 'addgroup':
				$this->set('sidebar', 'sidebar_groups.tpl');
				break;
		}
	}
	
	public function index()
	{
		$this->viewpilots();
	}
	
	
	public function viewpilots()
	{
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
				$pilotinfo = PilotData::getPilotData($pilotid);
				
				PilotData::DeletePilot($pilotid);
				
				$this->set('message', Lang::gs('pilot.deleted'));
				$this->render('core_success.tpl');
				
				
				LogData::addLog(Auth::$userinfo->pilotid, 'Deleted pilot '.PilotData::getPilotCode($pilotinfo->code, $pilotinfo->pilotid).' '.$pilotinfo->firstname .' ' .$pilotinfo->lastname);
				
				break;
			/* These are reloaded into the #pilotgroups ID
				so the entire groups list is refreshed
				*/
			case 'addgroup':
				
				$this->AddPilotToGroup();
				$this->SetGroupsData($this->post->pilotid);
				$this->render('pilots_groups.tpl');
				return;
				
				break;
			
			case 'removegroup':
				
				$this->RemovePilotGroup();
				
				$this->SetGroupsData($this->post->pilotid);
				$this->render('pilots_groups.tpl');
				
				return;
				
				break;
			
			case 'saveprofile':

				if($this->post->firstname == '' || $this->post->lastname == '')
				{
					$this->set('message', 'The first or lastname cannot be blank!');
					$this->render('core_error.tpl');
					return;
				}
				
				if(intval($this->post->retired) == 1)
				{
					$retired = true;
				}
				else
				{
					$retired = false;
				}
				
				
				# Just do this as one call
				//PilotData::ChangeName($this->post->pilotid, $this->post->firstname, $this->post->lastname);
				
				$params = array(
					'code' => $this->post->code,
					'firstname' => $this->post->firstname,
					'lastname' => $this->post->lastname,
					'email' => $this->post->email,
					'location' => $this->post->location,
					'hub' => $this->post->hub,
					'retired' => $retired,
					'totalhours' => $this->post->totalhours,
					'totalflights' => $this->post->totalflights,
					'totalpay' => $this->post->totalpay,
					'transferhours' => $this->post->transferhours,
				);
					
				PilotData::updateProfile($this->post->pilotid, $params);
				//PilotData::ReplaceFlightData($params); // Called with above now
				
				PilotData::SaveFields($this->post->pilotid, $_POST);
				
				/* Don't calculate a pilot's rank if this is set */
				if(Config::Get('RANKS_AUTOCALCULATE') == false)
				{
					PilotData::changePilotRank($this->post->pilotid, $this->post->rank);
				}
				else
				{
					RanksData::calculateUpdatePilotRank($this->post->pilotid);
				}
				
				StatsData::UpdateTotalHours();
				
				$this->set('message', 'Profile updated successfully');
				$this->render('core_success.tpl');
				
				
				$pilot = PilotData::getPilotData($this->post->pilotid);
				LogData::addLog(Auth::$userinfo->pilotid, 'Updated profile for '.PilotData::getPilotCode($pilot->code, $pilot->pilotid).' '.$pilot->firstname .' ' .$pilot->lastname);
				
				return;
				break;
		}
		
		if($this->get->action == 'viewoptions')
		{
			$this->ViewPilotDetails();
			return;
		}
		
		$this->ShowPilotsList();
	}
		
	public function pendingpilots()
	{
		if(isset($this->post->action))
		{
			switch($this->post->action)
			{
				case 'approvepilot':
					
					$this->ApprovePilot();
					
					break;
				case 'rejectpilot':
					
					$this->RejectPilot();
					
					break;
			}
		}

		$this->set('allpilots', PilotData::GetPendingPilots());
		$this->render('pilots_pending.tpl');
	}
	
	public function viewbids()
	{
		if($this->post->action == 'deletebid')
		{
			$ret = SchedulesData::RemoveBid($this->post->id);
			
			if($ret == true)
			{
				$this->set('message', 'Bid removed!');
				$this->render('core_success.tpl');
			}
			else
			{
				$this->set('message', 'There was an error!');
				$this->render('core_error.tpl');
			}
		}
		
		$this->set('allbids', SchedulesData::getAllBids());
		$this->render('pilots_viewallbids.tpl');
	}
	
	public function pilotgroups()
	{
		if(isset($this->post->action))
		{
			if($this->post->action == 'addgroup')
			{
				$this->AddGroupPost();
			}
			elseif($this->post->action == 'editgroup')
			{
				# Process
				$this->SaveGroup();
			}
		}
		
		$this->ShowGroups();
	}
	
	public function addgroup()
	{
		$this->set('title', 'Add a Group ');
		$this->set('action', 'addgroup');
		$this->set('permission_set', Config::Get('permission_set'));
		
		$this->render('groups_groupform.tpl');
	}
	
	public function editgroup()
	{
		if(!isset($this->get->groupid))
		{
			return;
		}
			
		$group_info = PilotGroups::GetGroup($this->get->groupid);
				
		$this->set('group', $group_info);
		$this->set('title', 'Editing '.$group_info->name);
		$this->set('action', 'editgroup');
		$this->set('permission_set', Config::Get('permission_set'));
		
		$this->render('groups_groupform.tpl');
	}
	
	public function pilotawards()
	{
		if(isset($this->post->action))
		{
			if($this->post->action == 'addaward')
			{			
				$this->AddAward();
			}
			elseif($this->post->action == 'deleteaward')
			{
				$this->DeleteAward();
			}
		}
						
		$this->set('allawards', AwardsData::GetPilotAwards($_REQUEST['pilotid']));
		$this->render('pilots_awards.tpl');
	}
		
	protected function ShowPilotsList()
	{
		$this->set('allpilots', PilotData::GetAllPilots(Vars::GET('letter')));
		$this->render('pilots_list.tpl');
	}
	
	protected function ViewPilotDetails()
	{
		//This is for the main tab
		$this->set('pilotinfo', PilotData::GetPilotData($this->get->pilotid));
		$this->set('customfields', PilotData::GetFieldData($this->get->pilotid, true));
		$this->set('allawards', AwardsData::GetPilotAwards($this->get->pilotid));
		$this->set('pireps', PIREPData::GetAllReportsForPilot($this->get->pilotid));
		$this->set('countries', Countries::getAllCountries());
		
		$this->SetGroupsData($this->get->pilotid);
		
		// For the PIREP list
		$this->set('pending', false);
		$this->set('load', 'pilotpireps');
		
		$this->render('pilots_detailtabs.tpl');
	}
	
	protected function SetGroupsData($pilotid)
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
		
		$this->set('pilotid', $pilotid);
		$this->set('pilotgroups', PilotData::GetPilotGroups($pilotid));
		$this->set('freegroups', $freegroups);
	}
	
	protected function AddGroupPost()
	{
		if($this->post->name == '')
		{
			$this->set('message', Lang::gs('group.no.name'));
			$this->render('core_error.tpl');
			return;
		}
		
		$permissions = 0;
		foreach($this->post->permissions as $perm)
		{
			$permissions = PilotGroups::set_permission($permissions, $perm);
		}
		
		$ret = PilotGroups::AddGroup($this->post->name, $permissions);
			
		if(DB::errno() != 0)
		{
			$this->set('message', sprintf(Lang::gs('error'), DB::$error));
			$this->render('core_error.tpl');
		}
		else
		{
			$this->set('message', sprintf(Lang::gs('group.added'), $this->post->name));
			$this->render('core_success.tpl');
			
			LogData::addLog(Auth::$userinfo->pilotid, 'Added group "'.$this->post->name.'"');
		}		
	}
	
	protected function SaveGroup()
	{		
		$permissions = 0;
		foreach($this->post->permissions as $perm)
		{
			$permissions = PilotGroups::set_permission($permissions, $perm);
		}
		
		PilotGroups::EditGroup($this->post->groupid, $this->post->name, $permissions);
		
		if(DB::errno() != 0)
		{
			$this->set('message', sprintf(Lang::gs('error'), DB::$error));
			$this->render('core_error.tpl');
		}
		else
		{
			$this->set('message', sprintf(Lang::gs('group.saved'), $this->post->name));
			$this->render('core_success.tpl');
			
			LogData::addLog(Auth::$userinfo->pilotid, 'Edited group "'.$this->post->name.'"');
		}		
	}
	
	
	protected function AddPilotToGroup()
	{
		if(PilotGroups::CheckUserInGroup($this->post->pilotid, $this->post->groupname))
		{
			$this->set('message', Lang::gs('group.pilot.already.in'));
			$this->render('core_error.tpl');
			return;
		}
		
		$ret = PilotGroups::AddUsertoGroup($this->post->pilotid, $this->post->groupname);
		
		if(DB::errno() != 0 )
		{
			$this->set('message', Lang::gs('group.add.error'));
			$this->render('core_error.tpl');
		}
		else
		{
			$this->set('message', Lang::gs('group.user.added'));
			$this->render('core_success.tpl');
			
			
			LogData::addLog(Auth::$userinfo->pilotid, 'Added pilot #'.$this->post->pilotid.' to group "'.$this->post->groupname.'"');
		}		
	}
	
	protected function RemovePilotGroup()
	{
		$pilotid = $this->post->pilotid;
		$groupid = $this->post->groupid;
		
		PilotGroups::RemoveUserFromGroup($pilotid, $groupid);
		
		if(DB::errno() != 0)
		{
			$this->set('message', 'There was an error removing');
			$this->render('core_error.tpl');
		}
		else
		{
			$this->set('message', 'Removed');
			$this->render('core_success.tpl');
			
			LogData::addLog(Auth::$userinfo->pilotid, 'Removed pilot #'.$this->post->pilotid.' from group "'.$this->post->groupid.'"');
		}
	}
	
	protected function ShowGroups()
	{
		$this->set('allgroups', PilotGroups::GetAllGroups());
		$this->render('groups_grouplist.tpl');
	}
	
	protected function ApprovePilot()
	{
		PilotData::AcceptPilot($this->post->id);
		RanksData::CalculatePilotRanks();
		
		$pilot = PilotData::GetPilotData($this->post->id);
		
		# Send pilot notification
		$subject = Lang::gs('email.register.accepted.subject');
		$this->set('pilot', $pilot);
		$message = Template::GetTemplate('email_registrationaccepted.tpl', true, true, true);
	
		Util::SendEmail($pilot->email, $subject, $message);
		
		LogData::addLog(Auth::$userinfo->pilotid, 'Approved '.PilotData::getPilotCode($pilot->code, $pilot->pilotid).' - ' .$pilot->firstname.' ' .$pilot->lastname);
	}
	
	protected function RejectPilot()
	{	
		$pilot = PilotData::GetPilotData($this->post->id);
		
		# Send pilot notification
		
		$subject = Lang::gs('email.register.rejected.subject');
				
		$this->set('pilot', $pilot);		
		$message = Template::Get('email_registrationdenied.tpl', true, true, true);
		
		Util::SendEmail($pilot->email, $subject, $message);
		
		# Reject in the end, since it's delted
		PilotData::RejectPilot($this->post->id);
		LogData::addLog(Auth::$userinfo->pilotid, 'Approved '.PilotData::getPilotCode($pilot->code, $pilot->pilotid).' - ' .$pilot->firstname.' ' .$pilot->lastname);
	}
	
	protected function ChangePassword()
	{
		$password1 = $this->post->password1;
		$password2 = $this->post->password2;
		
		// Check password length
		if(strlen($password1) <= 5)
		{
			$this->set('message', Lang::gs('password.wrong.length'));
			$this->render('core_message.tpl');
			return;
		}
		
		// Check is passwords are the same
		if($password1 != $password2)
		{
			$this->set('message', Lang::gs('password.no.match'));
			$this->render('core_message.tpl');
			return;
		}
		
		RegistrationData::ChangePassword($this->post->pilotid, $password1);
		
		if(DB::errno() != 0)
		{
			$this->set('message', 'There was an error, administrator has been notified');
			$this->render('core_error.tpl');
		}
		else
		{
			$this->set('message', Lang::gs('password.changed'));
			$this->render('core_success.tpl');
		}
		
		$pilot = PilotData::getPilotData($this->post->pilotid);
		LogData::addLog(Auth::$userinfo->pilotid, 'Changed the password for '.PilotData::getPilotCode($pilot->code, $pilot->pilotid).' - ' .$pilot->firstname.' ' .$pilot->lastname);
	}
	
	protected function AddAward()
	{
		
		if($this->post->awardid == '' || $this->post->pilotid == '')
			return;
					
		# Check if they already have this award
		$award = AwardsData::GetPilotAward($this->post->pilotid, $this->post->awardid);
		if($award)
		{
			$this->set('message', Lang::gs('award.exists'));
			$this->render('core_error.tpl');
			return;
		}
		
		AwardsData::AddAwardToPilot($this->post->pilotid, $this->post->awardid);
		
		$pilot = PilotData::getPilotData($this->post->pilotid);
		LogData::addLog(Auth::$userinfo->pilotid, 'Added and award to '.PilotData::getPilotCode($pilot->code, $pilot->pilotid).' - ' .$pilot->firstname.' ' .$pilot->lastname);
	}
	
	protected function DeleteAward()
	{
		AwardsData::DeletePilotAward($this->post->id);
		
		if($award)
		{
			$this->set('message', Lang::gs('award.deleted'));
			$this->render('core_success.tpl');
			return;
		}
	}
}