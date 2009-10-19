<?php
/**
 * Codon PHP Framework
 *	www.nsslive.net/codon
 * Software License Agreement (BSD License)
 *
 * Copyright (c) 2008 Nabeel Shahzad, nsslive.net
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2.  Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.nsslive.net/codon
 * @license BSD License
 * @package codon
 */

class Util
{
	
	public static $trace;
	
	/**
	 * Convert PHP 0-6 days to the Compact M T W R F S Su
	 */
	public static function GetDaysCompact($days)
	{
		$all_days = array('Su', 'M', 'T', 'W', 'Th', 'F', 'S', 'Su');

		foreach($all_days as $index=>$day)
		{
			$days = str_replace($index, $day.' ', $days);
		}
		
		return $days;
	}
	
	/**
	 * Convert PHP 0-6 days to the full date string
	 */
	public static function GetDaysLong($days)
	{
		$all_days = Config::Get('DAYS_LONG');
		foreach($all_days as $index=>$day)
		{
			$days = str_replace($index, $day.' ', $days);
		}
		
		return $days;
	}
	/**
	 * Add two time's together (1:30 + 1:30 = 3 hours, not 2.6)
	 *
	 * @param mixed $time1 Time one
	 * @param mixed $time2 Time two
	 * @return mixed Total time
	 *
	 */
	public static function AddTime($time1, $time2)
	{
		//self::$trace = array();
		$time1 = str_replace(':', '.', $time1);
		$time2 = str_replace(':', '.', $time2);
		
		//self::$trace[] = "Inputted as: $time1 + $time2";
		
		$time1 =  number_format($time1, 2);
		$time2 = number_format($time2, 2);
		
		$time1 = str_replace(',', '', $time1);
		$time2 = str_replace(',', '', $time2);
		
		//self::$trace[] = "After format: $time1 + $time2";
		
		$t1_ex = explode('.', $time1);
		$t2_ex = explode('.', $time2);
		
		# Check if the minutes are fractions
		# If they are (minutes > 60), convert to minutes
		if($t1_ex[1] > 60)
		{
			$t1_ex[1] = intval((intval($t1_ex[1])*60)/100);
		}
		
		if($t2_ex[1] > 60)
		{
			$t2_ex[1] = intval((intval($t2_ex[1])*60)/100);
		}
		
		//self::$trace[] = "After fraction check: $time1 + $time2";
		
		$hours = ($t1_ex[0] + $t2_ex[0]);
		$mins = ($t1_ex[1] + $t2_ex[1]);
					
		//self::$trace[] = "Added, before conversion: $hours:$mins";
			
		while($mins >= 60)
		{
			$hours++;
			$mins -= 60;		
		}
				
		//self::$trace[] = "Minutes left: $mins";
	
		# Add the 0 padding
		if(intval($mins) < 10)
			$mins = '0'.$mins;
		
		$time = number_format($hours.'.'.$mins, 2);
		$time = str_replace(',', '', $time);
		
		/*self::$trace[] = "Translated to $hours.$mins";
		self::$trace[] = "";*/
		
		return $time;
		#return $hours.'.'.$mins;		
	}
	
	
	/**
	 * Send an email 
	 *
	 * @param string $email Email Address to send to
	 * @param string $subject Email Subject
	 * @param string $message Email Message
	 * @param string $fromname From name (optional, will use SITE_NAME)
	 * @param string $fromemail From email (option, will use ADMIN_EMAIL)
	 * @return mixed 
	 *
	 */
	public static function SendEmail($email, $subject, $message, $fromname='', $fromemail='')
	{
		$mail = new PHPMailer(); 
		  
		$mail->From     = ($fromemail == '') ? ADMIN_EMAIL : $fromemail;  
		$mail->FromName = ($fromname == '') ? SITE_NAME : $fromname; 
		$mail->Mailer = 'mail';
		
		$message = nl2br($message);
		$alt = strip_tags($message);
		
		$mail->AddAddress($email); 
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->AltBody = $alt;
		
		$mail->Send();
		
		/*if($fromname != '' && $fromemail != '')
			$headers = "From: $fromname <$fromemail>\r\n";
		else
			$headers = "From: ".SITE_NAME." <".ADMIN_EMAIL.">\r\n";
			
		$headers .= "MIME-Version: 1.0\r\n";
		$boundary = uniqid("PHPVMSMAILER");
		$headers .= "Content-Type: multipart/alternative" .
		"; boundary = $boundary\r\n\r\n";
		$headers .= "This is a MIME encoded message.\r\n\r\n";
		//plain text version of message
		$headers .= "--$boundary\r\n" .
		"Content-Type: text/plain; charset=ISO-8859-1\r\n" .
		"Content-Transfer-Encoding: base64\r\n\r\n";
		$headers .= chunk_split(base64_encode($message));

		//HTML version of message
		$message = nl2br($message);
		$headers .= "--$boundary\r\n" .
					"Content-Type: text/html; charset=ISO-8859-1\r\n" .
					"Content-Transfer-Encoding: base64\r\n\r\n";
		$headers .= chunk_split(base64_encode($message));

		@mail($email, $subject, '', $headers);*/
	}
}