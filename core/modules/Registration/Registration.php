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
					$this->ProcessRegistration($extrafields);
				}
				else
				{
					Template::Show('registration_mainform.tpl');
				}
					
				$this->Register();
				break;
		}
	}
	
	function ProcessRegistration(&$extrafields)
	{	
		// check the registration
		$ret = RegistrationData::ProcessRegistration();
		
		// Yes, there was an error
		if($ret == false) 
		{
			Template::Show('registration_mainform.tpl');
		}
		else
		{
			if(RegistrationData::CompleteRegistration($extrafields) == false)
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
}
?>