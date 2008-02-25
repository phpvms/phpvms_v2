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
	
			//Get the extra fields, that'll show in the main form
			Template::Set('extrafields', $this->GetCustomFields());
			
			if(isset($_POST['submit_register']))
			{
				// check the registration
				$ret = $this->ProcessRegistration();
				
				// Yes, there was an error
				if($ret == false) 
				{
					Template::Show('registration_mainform.tpl');
				}
				else
				{
					Template::Show('registration_sentconfirmation.tpl');
				}
			}
			else
			{				
				Template::Show('registration_mainform.tpl');
			}
		}
	}
	
	/* Get the extra fields
	 */
	
	function GetCustomFields()
	{
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'customfields
					WHERE showonregister=\'y\'';
		
		return DB::get_results($sql);		
	}
	
	/* This function goes through the whole form and catalogs the errors
	 */	
	function ProcessRegistration()
	{
		$error = false;
			
		/* Check the firstname and last name
		 */
		if(Vars::POST('firstname') == '')
		{
			$error = true;
			Template::Set('firstname_error', true);
		}
		else
			Template::Set('firstname_error', '');
		
		/* Check the last name
		 */
		if(Vars::POST('lastname') == '')
		{
			$error = true;
			Template::Set('lastname_error', true);
		}
		else
			Template::Set('lastname_error', '');
			
		/* Check the email address
		 */
		if(Vars::POST('email') == '')
		{
			$error = true;
			Template::Set('email_error', true);
		}
		else
			Template::Set('email_error', '');
			
		/* Check the location
		 */
		if(Vars::POST('location') == '')
		{
			$error = true;
			Template::Set('location_error', true);
		}
		else
			Template::Set('location_error', '');		
		
		// Check password length
		if(Vars::POST('password1') < 6)
		{
			$error = true;
			Template::Set('password_error', 'The password is too short!');
		}
		else
			Template::Set('password_error', '');
			
		// Check is passwords are the same	
		if(Vars::POST('password1') != Vars::POST('password2'))
		{
			$error = true;
			Template::Set('password_error', 'The passwords do not match!');
		}
		else
			Template::Set('password_error', '');
			
		/* Check if they agreed to the statement
		 */
		if(!$_POST['agree'])
		{
			$error = true;
			Template::Set('agree_error', true);
		}
		else
			Template::Set('agree_error', '');
			
		if($error == true)
		{
			return false;
		}
		
		//No errors... process the rest
		
		return true;	
	}	
}
?>