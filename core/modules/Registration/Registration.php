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
 
class Registration extends CodonModule
{
	function HTMLHead()
	{
		/*Show our password strength checker
			*/
		if($this->get->page == 'register')
		{
			Template::ShowTemplate('registration_javascript.tpl');
		}
	}
		
	function Controller()
	{
	
		/* Verify the confirmation code from the email
		 */
		
		switch($this->get->page)
		{
			case '':
			
				if(Auth::LoggedIn()) // Make sure they don't over-ride it
				{
					Template::Show('login_already.tpl');
					return;
				}
					
				
				if(isset($_POST['submit']))
				{
					$this->ProcessRegistration();
				}
				else
				{
					$this->ShowForm();
				}
					
				//$this->ProcessRegistration();
				break;
				
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
		}
	}
	
	protected function ShowForm()
	{
		
		Template::Set('extrafields', RegistrationData::GetCustomFields());
		Template::Set('allairlines', OperationsData::GetAllAirlines(true));
		Template::Set('allhubs', OperationsData::GetAllHubs());
		Template::Set('countries', Countries::getAllCountries());
				
		# Just a simple addition
		$rand1 = rand(1, 10);
		$rand2 = rand(1, 10);
		
		Template::Set('rand1', $rand1);
		Template::Set('rand2', $rand2);		
		
		$tot = $rand1 + $rand2;
		SessionManager::Set('captcha_sum', $tot);
		
		Template::Show('registration_mainform.tpl');
		
	}
	
	protected function ProcessRegistration()
	{
			
		// Yes, there was an error
		if(!$this->VerifyData())
		{
			$this->ShowForm();
		}
		else
		{
			$firstname = $this->post->firstname;
			$lastname = $this->post->lastname;
			$email = $this->post->email;
			$code = $this->post->code;
			$location = $this->post->location;
			$hub = $this->post->hub;
			$password = $this->post->password1;
			
			if(CodonEvent::Dispatch('registration_precomplete', 'Registration', $_POST) == false)
			{
				return false;
			}
			
			$ret = RegistrationData::CheckUserEmail($email);
			
			if($ret)
			{
				Template::Set('error', 'This email address is already in use');
				Template::Show('registration_error.tpl');
				return false;
			}
			
			if(RegistrationData::AddUser($firstname, $lastname, $email, $code, $location, $hub, $password) == false)
			{
				Template::Set('error', RegistrationData::$error);
				Template::Show('registration_error.tpl');
			}
			else
			{
				RegistrationData::SendEmailConfirm($email, $firstname, $lastname);
				Template::Show('registration_sentconfirmation.tpl');
			}
			
			CodonEvent::Dispatch('registration_complete', 'Registration', $_POST);			
			
			$rss = new RSSFeed('Latest Pilot Registrations', SITE_URL, 'The latest pilot registrations');
			$allpilots = PilotData::GetLatestPilots();
			
			foreach($allpilots as $pilot)
			{
				$rss->AddItem('Pilot '.PilotData::GetPilotCode($pilot->code, $pilot->pilotid)
								. ' ('.$pilot->firstname .' ' . $pilot->lastname.')',
								SITE_URL.'/admin/index.php?admin=pendingpilots','','');
			}
		
		
			$rss->BuildFeed(LIB_PATH.'/rss/latestpilots.rss');
		}
	}

	/*
	 * Process all the registration data
	 */
	function VerifyData()
	{
		$error = false;
		
		$captcha = SessionManager::Get('captcha_sum');
		
		echo "captcha: $captcha entered: {$this->post->captcha}";
		if($this->post->captcha != $captcha)
		{
			$error = true;
			Template::Set('captcha_error', 'You failed the human test!');			
		}
		else
			Template::Set('captcha_error', '');
		
		/* Check the firstname and last name
		 */
		if($this->post->firstname == '')
		{
			$error = true;
			Template::Set('firstname_error', true);
		}
		else
			Template::Set('firstname_error', '');
		
		/* Check the last name
		 */
		if($this->post->lastname == '')
		{
			$error = true;
			Template::Set('lastname_error', true);
		}
		else
			Template::Set('lastname_error', '');
		
		/* Check the email address
		 */
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]*)$", $this->post->email) == false)
		{
			$error = true;
			Template::Set('email_error', true);
		}
		else
			Template::Set('email_error', '');
		
		/* Check the location
		 */
		if($this->post->location == '')
		{
			$error = true;
			Template::Set('location_error', true);
		}
		else
			Template::Set('location_error', '');
		
		// Check password length
		if(strlen($this->post->password1) <= 5)
		{
			$error = true;
			Template::Set('password_error', 'The password is too short!');
		}
		else
			Template::Set('password_error', '');
		
		// Check is passwords are the same
		if($this->post->password1 != $this->post->password2)
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