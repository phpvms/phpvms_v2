<?php

class Registration extends ModuleBase
{
	function HTMLHead()
	{
		/*Show our password strength checker
			*/
		if(Vars::GET('page') == 'register')
		{
			Template::ShowTemplate('registration_head_jscript.tpl');
		}
	}
	
	function NavBar()
	{   //TODO: only show if logged out
	
		if(!Auth::LoggedIn())
			echo '<li><a href="?page=register">Register</a></li>';
	}
	
	function Controller()
	{	
		if(Vars::GET('page') == 'register')
		{			
			if(Auth::LoggedIn()) // Make sure they don't over-ride it
				return;
	
			if(isset($_POST['submit_register']))
			{
				// check the registration
				$err = $this->ProcessRegistration();
				
				if($err == true) // Yes, there was an error
					Template::ShowTemplate('registration_mainform.tpl');
				else
					Template::ShowTemplate('registration_sentconfirmation.tpl');
			}
			else
			{				
				Template::ShowTemplate('registration_mainform.tpl');
			}
		}
	}
	
	/* This function goes through the whole form and catalogs the errors
	 */	
	function ProcessRegistration()
	{
		$error = false;
		
		if(!$_POST['agree'])
		{
			$error = true;
			Template::Set('agree_error', 'You did not agree to the terms and conditions!');
		}
		else
			Template::Set('agree_error', '');
		
		
		// Check password length
		if(Vars::POST('password1') < 6)
		{
			$error = true;
			Template::Set('password_tooshort', 'The password is too short!');
		}
		else
			Template::Set('password_tooshort', '');
			
			
		// Check is passwords are the same	
		if(Vars::POST('password1') != Vars::POST('password2'))
		{
			$error = true;
			Template::Set('password_mismatch', 'The passwords do not match!');
		}
		else
			Template::Set('password_mismatch', '');
			
			
		if($error == true)
			return false;
		
		//No errors... process the rest
		
		return true;	
	}	
}
?>