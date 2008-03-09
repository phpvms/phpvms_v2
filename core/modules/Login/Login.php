<?php

class Login extends ModuleBase
{
		
	function Controller()
	{		
		if(Vars::GET('page') == 'login')
		{			
			if(Auth::LoggedIn() == true)
			{
				echo 'You\'re already logged in!';
				return;	
			}		
			
			if(Vars::POST('action') == 'login')
			{
				$email = Vars::POST('email');
				$password = Vars::POST('password');
				
				if($email == '' || $password == '')
				{
					Template::Set('message', 'You must fill out both your username and password');
					Template::Show('login_form.tpl');
					return;
				}
				
				if(!Auth::ProcessLogin($email, $password))
				{
					Template::Set('message', Auth::$error_message);
					Template::Show('login_form.tpl');
					return;
				}
				else
				{
					//TODO: check if unconfirmed or not
					
					//TODO: add to sessions table 
					echo 'You have been logged in';
					Template::Set('redir', SITE_URL . '/' . Vars::POST('redir'));
					Template::Show('login_complete.tpl');
					return;
				}
			}
			
			Template::Set('redir', Vars::GET('redir'));
			Template::Show('login_form.tpl');
			
			//TODO: integrate "lost password" functionality
		}
	}
	
}
?>