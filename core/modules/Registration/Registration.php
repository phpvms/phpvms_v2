<?php

class Registration extends ModuleBase
{
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
		
		
		if($error == true)
			return false;
		
		//No errors... process the rest
		
		return true;	
	}	
}
?>