<?php

class Login extends ModuleBase
{
		
	function Controller()
	{	
	
		switch(Vars::GET('page'))
		{
			case 'login':
				if(Auth::LoggedIn() == true)
				{
					echo 'You\'re already logged in!';
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
						
			RegistrationData::ChangePassword($pilotdata->userid, substr(md5(date('mdYhs')), 0, 6));
			RegistrationData::SendEmailConfirm($pilotdata->email, $pilotdata->firstname, $pilotdata->lastname);
			
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
		
		$userinfo = Auth::ProcessLogin($email, $password);
		
		if(!is_object($userinfo))
		{
			Template::Set('message', Auth::$error_message);
			Template::Show('login_form.tpl');
			return false;
		}
		else
		{
			if($userinfo->confirmed == 'n')
			{
				//TODO: show template that they're not confirmed
			}
			else
			{
				//TODO: add to sessions table 
				SessionManager::AddData('loggedin', 'true');	
				SessionManager::AddData('userinfo', $userinfo);
				SessionManager::AddData('usergroups', PilotGroups::GetUserGroups($userinfo->userid));
				
				Template::Set('redir', SITE_URL . '/' . Vars::POST('redir'));
				Template::Show('login_complete.tpl');
			}
			
		}
		
		return true;
	}
}
?>