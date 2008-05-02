<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 *  This program is free software; you can redistribute it and/or modify it 
 *  under the terms of the GNU General Public License as published by the Free 
 *	Software Foundation; either version 2 of the License, or (at your option) 
 *	any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT 
 *  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 *	FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with 
 *	this program; if not, write to the:
 *		Free Software Foundation, Inc., 
 *		59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license GNU Public License http://opensource.org/licenses/gpl-license.php
 * @package module_pilotprofile
 */
 
class PilotProfile extends ModuleBase
{
	function Controller()
	{
		switch(Vars::GET('page'))
		{

			case 'profile':

				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}

				/* this comes from ?page=changepassword
				*/
				if($_POST['action'] == 'changepassword')
				{
					$this->ChangePassword();
				}

				Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
				Template::Set('report', PIREPData::GetLastReports(Auth::$userinfo->pilotid));
				Template::Set('nextrank', RanksData::GetNextRank(Auth::$userinfo->totalhours));
				Template::Set('userinfo', Auth::$userinfo);


				Template::Show('profile_main.tpl');
				break;

			case 'editprofile':

				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}

				if($_POST['action'] == 'saveprofile')
				{
					$this->SaveProfile();
				}

				Template::Set('userinfo', Auth::$userinfo);
				Template::Set('customfields', PilotData::GetFieldData(Auth::$pilotid, true));

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

		// save basic fields
		$email = Vars::POST('email');
		$location = Vars::POST('location');

		//TODO: check email validity
		if($email == '')
		{
			return;
		}

		PilotData::SaveProfile(Auth::$pilotid, $email, $location);
		PilotData::SaveFields(Auth::$pilotid, $_POST);
	}

	function ChangePassword()
	{
		// Verify
		if($_POST['oldpassword'] == '')
		{
			Template::Set('message', 'You must enter your current password');
			Template::Show('core_message.tpl');
			return;
		}

		if($_POST['password1'] != $_POST['password2'])
		{
			Template::Set('message', 'Your passwords do not match');
			Template::Show('core_message.tpl');
			return;
		}

		// Change
		$hash = md5($_POST['oldpassword'] . Auth::$userinfo->salt);

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