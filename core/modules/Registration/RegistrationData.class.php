<?php


class RegistrationData
{

	static public $salt;
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
	
	function CompleteRegistration($fields)
	{
		$firstname = Vars::POST('firstname');
		$lastname = Vars::POST('lastname');
		$email = Vars::POST('email');
		$location = Vars::POST('location');
		
		//Set the password, add some salt
		$salt = md5(date('His'));
		$password = md5(Vars::POST('password1') . $salt);
		
		self::$salt = $salt;
		
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
			return true;
			
		//Get customs fields
		foreach($fields as $field)
		{
			$value = Vars::POST($field->fieldname);
			if($value != '')
			{	
				$sql = "INSERT INTO ".TABLE_PREFIX."fieldvalues (fieldid, userid, value)
							VALUES ($field->fieldid, $userid, '$value')";
											
				DB::query();
			}
		}
	}
	
	function SendEmailConfirm()
	{
		$firstname = Vars::POST('firstname');
		$lastname = Vars::POST('lastname');
		$email = Vars::POST('email');
		$confid = self::$salt;
		
		//TODO: move this to a template!
		$message = "Dear $firstname $lastname,\nYour account have been made at " . SITE_NAME .", but must confirm it by clicking on this link:\n"

		. SITE_URL . "/index.php?page=confirm&confirmid=$confid" . "\n Or if you have HTML enabled email: <a href=\"" . SITE_URL . "/index.php?page=confirm&confirmid=$confid" . "\">Click here.</a>\n\nThanks!\n".SITE_NAME." Staff";

		//email them the confirmation            

		$headers = "From: ".SITE_NAME." <".ADMIN_EMAIL.">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$boundary = uniqid("VDAYRSVP");
		$headers .= "Content-Type: multipart/alternative" .
		"; boundary = $boundary\r\n\r\n";
		$headers .= "This is a MIME encoded message.\r\n\r\n";
		//plain text version of message
		$headers .= "--$boundary\r\n" .
		"Content-Type: text/plain; charset=ISO-8859-1\r\n" .
		"Content-Transfer-Encoding: base64\r\n\r\n";
		$headers .= chunk_split(base64_encode($message));
		
		//HTML version of message
		$headers .= "--$boundary\r\n" .
					"Content-Type: text/html; charset=ISO-8859-1\r\n" .
					"Content-Transfer-Encoding: base64\r\n\r\n";
		$headers .= chunk_split(base64_encode($message));
		
		mail($email, SITE_NAME . ' Registration', '', $headers);    
	}
	
	function ValidateConfirm()
	{
		$confid = Vars::GET('confirmid');
	
		$sql = "UPDATE ".TABLE_PREFIX."users SET confirmed='y', retired='n' WHERE salt='$confid'";
		$res = DB::query($sql);
		
		if(!$res && DB::$errno !=0)
		{			
			return false;
		}
		
		return true;
	}
}

?>