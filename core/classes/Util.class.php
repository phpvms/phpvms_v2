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
	function SendEmail($email, $subject, $message, $fromname='', $fromemail='')
	{
	
		if($fromname != '' && $fromemail != '')
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

		mail($email, $subject, '', $headers);
	}
}


# This function is from: http://us.php.net/money_format
# By Rafael M. Salvioni
if (!function_exists('money_format')) {
	function money_format($format, $number)
	{
		$regex  = array(
				'/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?(?:#([0-9]+))?',
				'(?:\.([0-9]+))?([in%])/'
				);
		$regex = implode('', $regex);
		if (setlocale(LC_MONETARY, null) == '') {
			setlocale(LC_MONETARY, '');
		}
		$locale = localeconv();
		$number = floatval($number);
		if (!preg_match($regex, $format, $fmatch)) {
			trigger_error("No format specified or invalid format",
					E_USER_WARNING);
			return $number;
		}
		$flags = array(
				'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
				$match[1] : ' ',
				'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
				'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
				$match[0] : '+',
				'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
				'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
				);
		$width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
		$left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
		$right      = trim($fmatch[4]) ? (int)$fmatch[4] :
			$locale['int_frac_digits'];
		$conversion = $fmatch[5];
		$positive = true;
		if ($number < 0) {
			$positive = false;
			$number  *= -1;
		}
		$letter = $positive ? 'p' : 'n';
		$prefix = $suffix = $cprefix = $csuffix = $signal = '';
		if (!$positive) {
			$signal = $locale['negative_sign'];
			switch (true) {
				case $locale['n_sign_posn'] == 0 || $flags['signal'] ==
					'(':
					$prefix = '(';
					$suffix = ')';
					break;
				case $locale['n_sign_posn'] == 1:
					$prefix = $signal;
					break;
				case $locale['n_sign_posn'] == 2:
					$suffix = $signal;
					break;
				case $locale['n_sign_posn'] == 3:
					$cprefix = $signal;
					break;
				case $locale['n_sign_posn'] == 4:
					$csuffix = $signal;
					break;
			}
		}
		if (!$flags['nosimbol']) {
			$currency  = $cprefix;
			$currency .= (
					$conversion == 'i' ?
					$locale['int_curr_symbol'] :
					$locale['currency_symbol']
					);
			$currency .= $csuffix;
		} else {
			$currency = '';
		}
		$space    = $locale["{$letter}_sep_by_space"] ? ' ' : '';
		
		$number = number_format($number, $right,
				$locale['mon_decimal_point'],
				$flags['nogroup'] ? '' :
				$locale['mon_thousands_sep']
				);
		$number = explode($locale['mon_decimal_point'], $number);
		
		$n = strlen($prefix) + strlen($currency);
		if ($left > 0 && $left > $n) {
			if ($flags['isleft']) {
				$number[0] .= str_repeat($flags['fillchar'], $left - $n);
			} else {
				$number[0] = str_repeat($flags['fillchar'], $left - $n) .
					$number[0];
			}
		}
		$number = implode($locale['mon_decimal_point'], $number);
		if ($locale["{$letter}_cs_precedes"]) {
			$number = $prefix . $currency . $space . $number . $suffix;
		} else {
			$number = $prefix . $number . $space . $currency . $suffix;
		}
		if ($width > 0) {
			$number = str_pad($number, $width, $flags['fillchar'],
					$flags['isleft'] ? STR_PAD_RIGHT : STR_PAD_LEFT);
		}
		$format = str_replace($fmatch[0], $number, $format);
		return $format;
	}
}