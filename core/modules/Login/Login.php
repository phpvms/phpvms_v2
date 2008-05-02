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
 * @package module_login
 */
 
class Login extends ModuleBase
{
		
	function Controller()
	{	
	
		switch(Vars::GET('page'))
		{
			case 'login':
				if(Auth::LoggedIn() == true)
				{
					echo '<p>You\'re already logged in!</p>';
					return;	
				}
				
				Template::Set('redir', Vars::GET('redir'));
				
				if(Vars::POST('action') == 'login')
				{
					$this->ProcessLogin();
				}
				else
				{
					Template::Show('login_form.tpl');
				}
				
				break;
				
			case 'logout':
			
				Auth::LogOut();
				
				/*redirect back to front page 
				*/
				Template::Set('redir', SITE_URL);
				Template::Show('login_complete.tpl');
				
				break;
			
			case 'forgotpassword':
			
				if(Vars::POST('action') == 'resetpass')
				{
					$this->ResetPassword();
					return;
				}
				
				$this->ForgotPassword();
				break;
		}	
	}

	function ForgotPassword()
	{
		Template::Show('login_forgotpassword.tpl');	
	}
	
	function ResetPassword()
	{
		$email = Vars::POST('email');
		
		if(!$email)
		{
			return false;
		}
		else
		{
			$pilotdata = PilotData::GetPilotByEmail($email);
			
			if(!$pilotdata)
			{
				Template::Show('login_notfound.tpl');
				return;
			}
			
			$newpw = substr(md5(date('mdYhs')), 0, 6);
			
			RegistrationData::ChangePassword($pilotdata->pilotid, $newpw);
						
			Template::Set('firstname', $pilotdata->firstname);
			Template::Set('lastname', $pilotdata->lastname);
			Template::Set('newpw', $newpw);
			
			$message = Template::GetTemplate('email_lostpassword.tpl', true);
			
			Util::SendEmail($pilotdata->email, 'Password Reset', $message);
			
			Template::Show('login_passwordreset.tpl');
		}		
	}
	
	function ProcessLogin()
	{
		$email = Vars::POST('email');
		$password = Vars::POST('password');
		
		if($email == '' || $password == '')
		{
			Template::Set('message', 'You must fill out both your username and password');
			Template::Show('login_form.tpl');
			return false;
		}
		
		if(!Auth::ProcessLogin($email, $password))
		{
			Template::Set('message', Auth::$error_message);
			Template::Show('login_form.tpl');
			return false;
		}
		else
		{
			//TODO: check if unconfirmed or not
			//TODO: add to sessions table 
			
			if(Auth::$userinfo->confirmed == 'n')
			{
				Auth::LogOut();
				
				// show error
			}
			else
			{
				//error_reporting(E_ALL);
							
				PilotData::UpdateLogin(SessionManager::GetValue('userinfo', 'pilotid'));
				
				Template::Set('redir', SITE_URL . '/' . Vars::POST('redir'));
				Template::Show('login_complete.tpl');
			}
			
			return;
		}
	}
	
}
?>