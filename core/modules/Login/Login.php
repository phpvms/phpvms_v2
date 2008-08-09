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
 */
 
class Login extends CodonModule
{
		
	function Controller()
	{
		switch($this->get->page)
		{
			case '':
			case 'login':
				if(Auth::LoggedIn() == true)
				{
					echo '<p>You\'re already logged in!</p>';
					return;
				}
				
				Template::Set('redir', $this->get->redir);
			
				if($this->post->action == 'login')
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
			
				if($this->post->action == 'resetpass')
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
		$email = $this->post->emails;
		
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
		$email = $this->post->email;
		$password = $this->post->password;
		
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

			if(Auth::$userinfo->confirmed == PILOT_PENDING)
			{
				Template::Show('login_unconfirmed.tpl');
				Auth::LogOut();
				
				// show error
			}
			elseif(Auth::$userinfo->confirmed == PILOT_REJECTED)
			{
				Template::Show('login_rejected.tpl');
				Auth::LogOut();
			}
			else
			{
				//error_reporting(E_ALL);
							
				PilotData::UpdateLogin(SessionManager::GetValue('userinfo', 'pilotid'));
				
				Template::Set('redir', SITE_URL . '/' . $this->post->redir);
				Template::Show('login_complete.tpl');
				
				CodonEvent::Dispatch('login_success', 'Login');
			}
			
			return;
		}
	}
	
}
?>