<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 *  This program is free software; you can redistribute it and/or modify it 
 *  under the terms of the GNU General Public License as published by the Free 
 *	Software Foundation; either version 2 of the License, or (at your option) 
 *	any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT 
 *  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 *	FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with 
 *	this program; if not, write to the:
 *		Free Software Foundation, Inc., 
 *		59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license GNU Public License http://opensource.org/licenses/gpl-license.php
 * @package core_api
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
	
	function AddUser($firstname, $lastname, $email, $code, $location, $password, $confirm=0)
	{		
		//Set the password, add some salt
		$salt = md5(date('His'));
		$password = md5($password . $salt);
		
		//Stuff it into here, the confirmation email will use it.
		self::$salt = $salt;

		$firstname = ucwords($firstname);
		$lastname = ucwords($lastname);
		//Add this stuff in
		
		$sql = "INSERT INTO ".TABLE_PREFIX."pilots (firstname, lastname, email, code, location, password, salt, confirmed)
					VALUES ('$firstname', '$lastname', '$email', '$code', '$location', '$password', '$salt', $confirm)";
		
		$res = DB::query($sql);
		
		if(!$res)
		{
			if(DB::$errno == 1062)
			{
				self::$error = 'This email address is already registered';
				
				return false;
			}
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
		
		return true;
	}
	
	function ChangePassword($pilotid, $newpassword)
	{
		$salt = md5(date('His'));

		$password = md5($newpassword . $salt);
		
		self::$salt = $salt;
		
		$sql = "UPDATE " . TABLE_PREFIX ."pilots SET password='$password', salt='$salt', confirmed='y' WHERE pilotid=$pilotid";
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
	
		$sql = "UPDATE ".TABLE_PREFIX."pilots SET confirmed='y', retired='n' WHERE salt='$confid'";
		$res = DB::query($sql);
		
		if(!$res && DB::$errno !=0)
		{
			return false;
		}
		
		return true;
	}
}

?>