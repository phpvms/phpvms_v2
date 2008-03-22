<?php
/**
 * RegistrationData
 *
 * Model for any registration data
 * 
 * @author Nabeel Shahzad <contact@phpvms.net>
 * @copyright Copyright (c) 2008, phpVMS Project
 * @license http://www.phpvms.net/license.php
 * 
 * @package RegistrationData
 */

class RegistrationData
{

	static public $salt;
	static public $error;
	
	/* Get the extra fields
	 */
	function GetCustomFields()
	{
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'customfields
				WHERE showonregister=\'y\'';
		
		return DB::get_results($sql);		
	}
	
	function AddUser($fields)
	{
		$firstname = Vars::POST('firstname');
		$lastname = Vars::POST('lastname');
		$email = Vars::POST('email');
		$location = Vars::POST('location');
		
		//Set the password, add some salt
		$salt = md5(date('His'));
		$password = md5(Vars::POST('password1') . $salt);
		
		//Stuff it into here, the confirmation email will use it.
		self::$salt = $salt;
		
		//Add this stuff in
		
		$sql = "INSERT INTO ".TABLE_PREFIX."users (firstname, lastname, email, location, password, salt, confirmed)
					VALUES ('$firstname', '$lastname', '$email', '$location', '$password', '$salt', 'n')";
		
		$res = DB::query($sql);
		if(!$res)
		{
			if(DB::$errno == 1062)
				self::$error = 'This email address is already registered';
			else	
			{
				self::$error = 'An error occured, please contact the administrator';
				//TODO: email admin
			}
						
			return false;
		}
		
		//Grab the new pilotid, we need it to insert those "custom fields"
		$pilotid = DB::$insert_id;
		
		if(!$fields)
			return true;
			
		//Get customs fields
		foreach($fields as $field)
		{
			$value = Vars::POST($field->fieldname);
			if($value != '')
			{	
				$sql = "INSERT INTO ".TABLE_PREFIX."fieldvalues (fieldid, pilotid, value)
							VALUES ($field->fieldid, $pilotid, '$value')";
											
				DB::query();
			}
		}
	}
	
	function ChangePassword($pilotid, $newpassword)
	{
		$salt = md5(date('His'));

		$password = md5($newpassword . $salt);
		
		self::$salt = $salt;
		
		$sql = "UPDATE " . TABLE_PREFIX ."users SET password='$password', salt='$salt', confirmed='y' WHERE pilotid=$pilotid";
		return DB::query($sql);		
	}
	
	function SendEmailConfirm($email, $firstname, $lastname, $newpw='')
	{
		/*$firstname = Vars::POST('firstname');
		$lastname = Vars::POST('lastname');
		$email = Vars::POST('email');*/
		$confid = self::$salt;
		
		$subject = SITE_NAME . ' Registration';
		 
		Template::Set('firstname', $firstname);
		Template::Set('lastname', $lastname);
		Template::Set('confid', $confid);
				
		$message = Template::GetTemplate('email_registered.tpl', true);
				
		//email them the confirmation            
		Util::SendEmail($email, $subject, $message);		
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