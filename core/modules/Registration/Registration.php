<?php

class Registration extends ModuleBase
{
	function HTMLHead()
	{
		/*Show our password strength checker
			*/
		if(Vars::GET('page') == 'register')
		{
			Template::ShowTemplate('registration_javascript.tpl');
		}
	}
		
	function Controller()
	{	
	
		/* Verify the confirmation code from the email
		 */
		
		switch(Vars::GET('page'))
		{
			case 'confirm':
				
				if(RegistrationData::ValidateConfirm())
				{
					Template::Show('registration_complete.tpl');
				}
				else
				{
					//TODO: error template, notify admin
					DB::debug();
				}
				
				break;
			
			case 'register':
			
				if(Auth::LoggedIn()) // Make sure they don't over-ride it
					break;
					
				$extrafields = RegistrationData::GetCustomFields();
				Template::Set('extrafields', $extrafields);
				
				if(isset($_POST['submit_register']))
				{
					$this->ProcessRegistration();
				}
				else
				{
					Template::Show('registration_mainform.tpl');
				}
					
				//$this->ProcessRegistration();
				break;
		}
	}
	
	function ProcessRegistration()
	{	
			
		// Yes, there was an error
		if(!$this->VerifyData()) 
		{
			Template::Show('registration_mainform.tpl');
		}
		else
		{
			if(RegistrationData::AddUser() == false)
			{
				Template::Set('error', RegistrationData::$error);
				Template::Show('registration_error.tpl');
			}
			else
			{
				$firstname = Vars::POST('firstname');
				$lastname = Vars::POST('lastname');
				$email = Vars::POST('email');
				
				RegistrationData::SendEmailConfirm($email, $firstname, $lastname);
				Template::Show('registration_sentconfirmation.tpl');
			}
		}
	}
	
	/*
	 * Process all the registration data
	 */	
	function VerifyData()
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
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", Vars::POST('email')) == false)
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
		if(strlen(Vars::POST('password1')) <= 5)
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
		
		return true;	
	}	
}
?>