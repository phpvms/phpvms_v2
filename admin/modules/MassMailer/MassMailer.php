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
 * 
 *This particular section was built by Cale Cunningham; basically a modified version
 *of the contact form found in the core.
 */

class MassMailer extends CodonModule 
{
	
	public function Controller()
	{
		
		if($this->post->submit)
		{
			
			if($this->post->subject == '' || trim($this->post->message) == '')
			{
				Template::Set('message', 'You must enter a subject and message!');
				Template::Show('core_error.tpl');
				return;
			}
			
			$subject = $this->post->subject;
			$message = DB::escape($this->post->message) . PHP_EOL . PHP_EOL;
			

			
			//Begin the nice long assembly of e-mail addresses
			$pilotarray = PilotData::GetAllPilots();

			$mail = new PHPMailer(); 
			$mail->Subject = $subject;
			$mail->From     = ($fromemail == '') ? ADMIN_EMAIL : $fromemail;  
			$mail->FromName = ($fromname == '') ? SITE_NAME : $fromname; 
			$mail->Mailer = 'mail';

			foreach($pilotarray as $pilot)
			{
				# Variable replacements
				$send_message = str_replace('%%PILOT_NAME%%', $pilot->firstname.' '.$pilot->lastname, $message);
				$send_message = str_replace('%%PILOT_ID%%', PilotData::GetPilotCode($pilot->code, $pilot->pilotid));
			    
				$mail->Body = $send_message;
				$mail->AltBody = strip_tags($send_message);
				
				$mail->AddAddress($pilot->email);                 
				$mail->Send();
				
				$mail->ClearAddresses();
			}
			
			
			Template::Show('mailer_sent.tpl');
			return;
		}		
		
		
		Template::Show('mailer_form.tpl');
	}
	
}
