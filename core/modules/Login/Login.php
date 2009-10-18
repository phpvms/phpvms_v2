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
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->login();
	}
	
	public function login($redir='')
	{
		if(Auth::LoggedIn() == true)
		{
			Template::Show('login_already.tpl');
			return;
		}
		
		Template::Set('redir', $redir);
	
		if($this->post->action == 'login')
		{
			$this->ProcessLogin();
		}
		else
		{
			Template::Show('login_form.tpl');
		}
	}
	
	public function logout()
	{
		Auth::LogOut();
		Template::Show('login_complete.tpl');
	}
	
	public function forgotpassword()
	{
		if($this->post->action == 'resetpass')
		{
			$this->ResetPassword();
			return;
		}
		
		Template::Show('login_forgotpassword.tpl');
	}
	
	public function ResetPassword()
	{
		$email = $this->post->email;
		
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
	
	public function ProcessLogin()
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
				$pilotid = Auth::$userinfo->pilotid;
				PilotData::UpdateLogin($pilotid);
				
				# If they choose to be "remembered", then assign a cookie
				if($this->post->remember == 'on')
				{
					$session_id = Auth::set_session($pilotid);
					$cookie = "{$session_id}|{$pilotid}|{$_SERVER['REMOTE_ADDR']}";
					$res = setrawcookie(VMS_AUTH_COOKIE, $cookie, time() + Config::Get('SESSION_LOGIN_TIME'), '/');
				}
				
				Template::Set('redir', SITE_URL . '/' . $this->post->redir);
				Template::Show('login_complete.tpl');
				
				CodonEvent::Dispatch('login_success', 'Login');
			}
			
			return;
		}
	}
}