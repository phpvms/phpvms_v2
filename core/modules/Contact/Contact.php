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
	
	public function Controller()
	{
		
		if($this->post->submit)
		{
			
			#echo "Session: ".SessionManager::Get('captcha_sum');
			
			if($this->post->loggedin == 'false')
			{
				// Check the captcha thingy
				if($this->post->captcha != SessionManager::Get('captcha_sum'))
				{
					Template::Set('message', 'You failed the captcha test!');
					Template::Show('core_error.tpl');
					return;
				}
			}
			
			$subject = 'New message from '.$this->post->name.' - "'.$this->post->subject.'"';
			$message = DB::escape($message). PHP_EOL;
			
			foreach($_POST as $field=>$value)
			{
				$message.="[ $field ] = $value".PHP_EOL;
			}
			
			Util::SendEmail(ADMIN_EMAIL, $subject, $message, 
								$this->post->name, $this->post->email);
			
			return;
		}		
		
		Template::Show('contact_form.tpl');
	}
	
}
