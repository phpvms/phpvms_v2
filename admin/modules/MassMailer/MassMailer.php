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
	
	public function HTMLHead()
	{
		$this->set('sidebar', 'sidebar_mailer.tpl');
	}
	
	public function index()
	{
		
		if($this->post->submit)
		{
			echo '<h3>Sending email</h3>';
			if($this->post->subject == '' || trim($this->post->message) == '')
			{
				$this->set('message', 'You must enter a subject and message!');
				$this->render('core_error.tpl');
				return;
			}
			
			echo 'Sending email...<br />';
			
			$subject = DB::escape($this->post->subject);
			$message = html_entity_decode($this->post->message). PHP_EOL . PHP_EOL;
			
			
			# Do some quick fixing of obvious formatting errors
			$message = str_replace('<br>', '<br />', $message);
			
			//Begin the nice long assembly of e-mail addresses
			$pilotarray = PilotData::GetAllPilots();

			$mail = new PHPMailer(); 
			$mail->Subject = $subject;
			$mail->From     = ($fromemail == '') ? ADMIN_EMAIL : $fromemail;  
			$mail->FromName = ($fromname == '') ? SITE_NAME : $fromname; 
			$mail->Mailer = 'mail';

			foreach($pilotarray as $pilot)
			{
				echo 'Sending for '.$pilot->firstname.' '.$pilot->lastname.'<br />';
				
				# Variable replacements
				$send_message = str_replace('{PILOT_FNAME}', $pilot->firstname, $message);
				$send_message = str_replace('{PILOT_LNAME}', $pilot->lastname, $send_message);
				$send_message = str_replace('{PILOT_ID}', PilotData::GetPilotCode($pilot->code, $pilot->pilotid), $send_message);

				$mail->MsgHTML($send_message);
				
				$mail->AddAddress($pilot->email);                 
				$mail->Send();
				
				$mail->ClearAddresses();
			}
			
			echo 'Complete!';
			return;
		}		
		
		
		$this->render('mailer_form.tpl');
	}
	
}
