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

class Contact extends CodonModule 
{
	
	public function index()
	{
		if($this->post->submit)
		{
			$captcha = SessionManager::Get('captcha_sum');
			
			if($this->post->loggedin == 'false')
			{		
				// Check the captcha thingy
				if($this->post->captcha != $captcha)
				{
					$this->set('message', 'You failed the captcha test!');
					$this->render('core_error.tpl');
					return;
				}
				
				# Make sure they entered an email address
				if(trim($this->post->name) == '' 
					|| trim($this->post->email) == '')
				{
					$this->set('message', 'You must enter a name and email!');
					$this->render('core_error.tpl');
					return;
				}
			}
			
			if($this->post->subject == '' || trim($this->post->message) == '')
			{
				$this->set('message', 'You must enter a subject and message!');
				$this->render('core_error.tpl');
				return;
			}
			
			$subject = 'New message from '.$this->post->name.' - "'.$this->post->subject.'"';
			$message = DB::escape($this->post->message) . PHP_EOL . PHP_EOL;
			
			foreach($_POST as $field=>$value)
			{
				$message.="-$field = $value".PHP_EOL;
			}
			
			Util::SendEmail(ADMIN_EMAIL, $subject, $message);
			
			$this->render('contact_sent.tpl');
			return;
		}		
		
		# Just a simple addition
		$rand1 = rand(1, 10);
		$rand2 = rand(1, 10);
		
		$this->set('rand1', $rand1);
		$this->set('rand2', $rand2);		
		
		$tot = $rand1 + $rand2;
		//echo "total: $tot <br />";
		SessionManager::Set('captcha_sum', $tot);
		
		//echo 'output of $_SESSION: <br />';
		//print_r($_SESSION);
		
		$this->render('contact_form.tpl');
	}
	
}
