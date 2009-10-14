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
 
class Profile extends CodonModule
{
	function Controller()
	{
		switch($this->get->page)
		{
			/**
			 * This is the public profile for the pilot
			 */
			/**
			 * This is the profile for the pilot
			 *	They can edit their profile from  here.
			 */
			case '':

				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}

				/*
				 * This is from /profile/editprofile
				 */
				if($this->post->action == 'saveprofile')
				{
					$this->SaveProfile();
					
					Template::Set('message', 'Profile saved!');
					Template::Show('core_success.tpl');
				}
				
				/* this comes from /profile/changepassword
				*/
				if($this->post->action == 'changepassword')
				{
					$this->ChangePassword();
					
					Template::Set('message', 'Password changed!');
					Template::Show('core_success.tpl');
				}
				
				if(Config::Get('TRANSFER_HOURS_IN_RANKS') == true)
				{
					$totalhours = intval(Auth::$userinfo->totalhours) + intval(Auth::$userinfo->transferhours);
				}
				else
				{
					$totalhours = Auth::$userinfo->totalhours;
				}
				
				Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
				Template::Set('report', PIREPData::GetLastReports(Auth::$userinfo->pilotid));
				Template::Set('nextrank', RanksData::GetNextRank($totalhours));
				Template::Set('allawards', AwardsData::GetPilotAwards(Auth::$userinfo->pilotid));
				Template::Set('userinfo', Auth::$userinfo);
				Template::Set('pilot_hours', $totalhours);

				Template::Show('profile_main.tpl');
				
				CodonEvent::Dispatch('profile_viewed', 'Profile');
				
				break;
								
			/*
			 * View a different pilot's profile
			 */
			case 'view':
			
				$pilotid = $this->get->pilotid;
	
				if(preg_match('/^([A-Za-z]{3})(\d*)/', $pilotid, $matches) > 0)
				{
					$pilotid = $matches[2];
				}
				
				$userinfo = PilotData::GetPilotData($pilotid);
				
				Template::Set('userinfo', $userinfo);
				Template::Set('allfields', PilotData::GetFieldData($pilotid, false));
				Template::Set('pireps', PIREPData::GetAllReportsForPilot($pilotid));
				Template::Set('pilotcode', PilotData::GetPilotCode($userinfo->code, $userinfo->pilotid));
				Template::Set('allawards', AwardsData::GetPilotAwards($userinfo->pilotid));
				
				Template::Show('pilot_public_profile.tpl');
				Template::Show('pireps_viewall.tpl');
				
				break;
				
			case 'editprofile':

				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}

				Template::Set('userinfo', Auth::$userinfo);
				Template::Set('customfields', PilotData::GetFieldData(Auth::$pilotid, true));
				Template::Set('bgimages', PilotData::GetBackgroundImages());
				Template::Set('countries', Countries::getAllCountries());
				Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));

				Template::Show('profile_edit.tpl');
				break;

			case 'changepassword':

				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}

				Template::Show('profile_changepassword.tpl');
				break;

		}
	}

	function SaveProfile()
	{
		$userinfo = Auth::$userinfo;
		
		//TODO: check email validity
		if($this->post->email == '')
		{
			return;
		}

		$data = array(			
			'pilotid' => Auth::$pilotid,
			'email' => $this->post->email,
			'location' => $this->post->location,
			'bgimage' => $this->post->bgimage
			);
			
		PilotData::SaveProfile($data);
		PilotData::SaveFields(Auth::$pilotid, $_POST);
		
		PilotData::SaveAvatar($userinfo->code, $userinfo->pilotid, $_FILES);
	}

	function ChangePassword()
	{
		// Verify
		if($this->post->oldpassword == '')
		{
			Template::Set('message', 'You must enter your current password');
			Template::Show('core_message.tpl');
			return;
		}

		if($this->post->password1 != $this->post->password2)
		{
			Template::Set('message', 'Your passwords do not match');
			Template::Show('core_message.tpl');
			return;
		}

		// Change
		$hash = md5($this->post->oldpassword . Auth::$userinfo->salt);

		if($hash == Auth::$userinfo->password)
		{
			RegistrationData::ChangePassword(Auth::$pilotid, $_POST['password1']);
			Template::Set('message', 'Your password has been reset');
		}
		else
		{
			Template::Set('message', 'You entered an invalid password');
		}

		Template::Show('core_message.tpl');
	}
}
?>