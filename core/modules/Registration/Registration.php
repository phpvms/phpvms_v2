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
	public function HTMLHead()
	{
		/*Show our password strength checker
			*/
		if($this->get->page == 'register')
		{
			$this->renderTemplate('registration_javascript.tpl');
		}
	}
		
		
	public function index()
	{
		if(Auth::LoggedIn()) // Make sure they don't over-ride it
		{
			$this->render('login_already.tpl');
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
	}
		
	protected function ShowForm()
	{
		
		$this->set('extrafields', RegistrationData::GetCustomFields());
		$this->set('allairlines', OperationsData::GetAllAirlines(true));
		$this->set('allhubs', OperationsData::GetAllHubs());
		$this->set('countries', Countries::getAllCountries());
				
		# Just a simple addition
		$rand1 = rand(1, 10);
		$rand2 = rand(1, 10);
		
		$this->set('rand1', $rand1);
		$this->set('rand2', $rand2);		
		
		$tot = $rand1 + $rand2;
		SessionManager::Set('captcha_sum', $tot);
		
		$this->render('registration_mainform.tpl');
		
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
			$data = array(
				'firstname' => $this->post->firstname,
				'lastname' => $this->post->lastname,
				'email' => $this->post->email,
				'password' => $this->post->password1,
				'code' => $this->post->code,
				'location' => $this->post->location,
				'hub' => $this->post->hub,
				'confirm' => false);
				
			if(CodonEvent::Dispatch('registration_precomplete', 'Registration', $_POST) == false)
			{
				return false;
			}
			
			$ret = RegistrationData::CheckUserEmail($data['email']);
			
			if($ret)
			{
				$this->set('error', Lang::gs('email.inuse'));
				$this->render('registration_error.tpl');
				return false;
			}
			
			
			if(RegistrationData::AddUser($data) == false)
			{
				$this->set('error', RegistrationData::$error);
				$this->render('registration_error.tpl');
			}
			else
			{
				RegistrationData::SendEmailConfirm($email, $firstname, $lastname);
				$this->render('registration_sentconfirmation.tpl');
			}
			
			CodonEvent::Dispatch('registration_complete', 'Registration', $_POST);
			
			// Registration email/show user is waiting for confirmation
			$sub = 'A user has registered';
			$message = "There user {$data['firstname']} {$data['lastname']} ({$data['email']}) has registered, and is awaiting confirmation.";
			Util::SendEmail(ADMIN_EMAIL, $sub, $message);
			
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
	protected function VerifyData()
	{
		$error = false;
		
		$captcha = SessionManager::Get('captcha_sum');
		
		if($this->post->captcha != $captcha)
		{
			$error = true;
			$this->set('captcha_error', 'You failed the human test!');			
		}
		else
			$this->set('captcha_error', '');
		
		/* Check the firstname and last name
		 */
		if($this->post->firstname == '')
		{
			$error = true;
			$this->set('firstname_error', true);
		}
		else
			$this->set('firstname_error', '');
		
		/* Check the last name
		 */
		if($this->post->lastname == '')
		{
			$error = true;
			$this->set('lastname_error', true);
		}
		else
			$this->set('lastname_error', '');
		
		/* Check the email address
		 */
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]*)$", $this->post->email) == false)
		{
			$error = true;
			$this->set('email_error', true);
		}
		else
			$this->set('email_error', '');
		
		/* Check the location
		 */
		if($this->post->location == '')
		{
			$error = true;
			$this->set('location_error', true);
		}
		else
			$this->set('location_error', '');
		
		// Check password length
		if(strlen($this->post->password1) <= 5)
		{
			$error = true;
			$this->set('password_error', 'The password is too short!');
		}
		else
			$this->set('password_error', '');
		
		// Check is passwords are the same
		if($this->post->password1 != $this->post->password2)
		{
			$error = true;
			$this->set('password_error', 'The passwords do not match!');
		}
		else
			$this->set('password_error', '');
		
		/* Check if they agreed to the statement

		if(!$_POST['agree'])
		{
			$error = true;
			$this->set('agree_error', true);
		}
		else
			$this->set('agree_error', '');
		 */
		if($error == true)
		{
			return false;
		}
		
		return true;
	}
}