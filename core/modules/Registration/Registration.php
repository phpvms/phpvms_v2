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
 * @package module_registration
 */
 
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
			/*
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
			*/
			
			case 'register':
			
				if(Auth::LoggedIn()) // Make sure they don't over-ride it
					break;
					
					
				Template::Set('extrafields', RegistrationData::GetCustomFields());
				Template::Set('allairlines', OperationsData::GetAllAirlines());
				
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
			$firstname = Vars::POST('firstname');
			$lastname = Vars::POST('lastname');
			$email = Vars::POST('email');
			$code = Vars::POST('code');
			$location = Vars::POST('location');
			$password = Vars::POST('password1');
			
			if(RegistrationData::AddUser($firstname, $lastname, $email, $code, $location, $password) == false)
			{
				Template::Set('error', RegistrationData::$error);
				Template::Show('registration_error.tpl');
			}
			else
			{				
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

		if(!$_POST['agree'])
		{
			$error = true;
			Template::Set('agree_error', true);
		}
		else
			Template::Set('agree_error', '');
		 */
		if($error == true)
		{
			return false;
		}
		
		return true;	
	}	
}
?>