<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class RegistrationData
{

	static public $salt;
	static public $error;
	
	/**
	 * Get all of the custom fields that will show up
	 *	during the registration
	 */
	public static function GetCustomFields($getall=false)
	{
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'customfields';
		
		if($getall == false)
			$sql .=' WHERE showonregister=1';
		
		return DB::get_results($sql);
	}
	
	/**
	 * Add a  User
	 */
	public static function AddUser($firstname, $lastname, $email, $code, $location, $hub, $password, $confirm=false)
	{
		//Set the password, add some salt
		$salt = md5(date('His'));
		$password = md5($password . $salt);
		
		//Stuff it into here, the confirmation email will use it.
		self::$salt = $salt;

		$firstname = ucwords($firstname);
		$lastname = ucwords($lastname);
		//Add this stuff in
		
		if($confirm == true)
			$confirm = 1;
		else
			$confirm = 0;
		
		$sql = "INSERT INTO ".TABLE_PREFIX."pilots (firstname, lastname, email,
					code, location, hub, password, salt, confirmed)
				  VALUES ('$firstname', '$lastname', '$email', '$code',
							'$location', '$hub', '$password', '$salt', $confirm)";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
		{
			if(DB::errno() == 1062)
			{
				self::$error = 'This email address is already registered';
				return false;
			}
			
			return false;
		}
		
		//Grab the new pilotid, we need it to insert those "custom fields"
		$pilotid = DB::$insert_id;
		$fields = self::GetCustomFields();
					
		//Get customs fields
		if(!$fields)
			return true;
			
		foreach($fields as $field)
		{
			$value = Vars::POST($field->fieldname);
		
			if($value != '')
			{
				$sql = "INSERT INTO ".TABLE_PREFIX."fieldvalues (fieldid, pilotid, value)
							VALUES ($field->fieldid, $pilotid, '$value')";
											
				DB::query($sql);
			}
		}
		
		RanksData::CalculateUpdatePilotRank($pilotid);
		PilotData::GenerateSignature($pilotid);
		
		return true;
	}
	
	public static function ChangePassword($pilotid, $newpassword)
	{
		$salt = md5(date('His'));

		$password = md5($newpassword . $salt);
		
		self::$salt = $salt;
		
		$sql = "UPDATE " . TABLE_PREFIX ."pilots SET password='$password', salt='$salt', confirmed='y' WHERE pilotid=$pilotid";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public static function SendEmailConfirm($email, $firstname, $lastname, $newpw='')
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
	
	public static function ValidateConfirm()
	{
		$confid = Vars::GET('confirmid');
	
		$sql = "UPDATE ".TABLE_PREFIX."pilots SET confirmed=1, retired=0 WHERE salt='$confid'";
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
}
?>