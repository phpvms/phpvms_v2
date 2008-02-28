<?php


class RegistrationData
{
	/* Get the extra fields
	 */
	function GetCustomFields()
	{
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'customfields
				WHERE showonregister=\'y\'';
		
		return DB::get_results($sql);		
	}

	/*
	 * Process all the registration data
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
	
	
	function CompleteRegistration($fields)
	{
		$firstname = Vars::POST('firstname');
		$lastname = Vars::POST('lastname');
		$email = Vars::POST('email');
		$location = Vars::POST('location');
		
		//Set the password, add some salt
		$salt = md5(date('His'));
		$password = md5(Vars::POST('password1') . $salt);
		
		//Add this stuff in
		
		$sql = "INSERT INTO ".TABLE_PREFIX."users (firstname, lastname, email, location, password, salt, confirmed)
					VALUES ('$firstname', '$lastname', '$email', '$location', '$password', '$salt', 'n')";
		
		$res = DB::query($sql);
		if(!$res)
		{
			DB::debug();
			return false;
		}
		
		//Grab the new userid, we need it to insert those "custom fields"
		$userid = DB::$insert_id;
		
		if(!$fields)
			return;
			
		//Get customs fields
		foreach($fields as $field)
		{
			if(Vars::POST($field->fieldname)!='')
			{	
				$sql = '';
				
				DB::query();
			}
		}
	}
}

?>