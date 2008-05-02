<?
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
 * @package codon_core
 */

/**
  * Session Manager Module
  *		This handles the backbone of the authentication stuff
  *
  * Sona CMS Framework
  *
  * @author Nabeel Shahzad
  * @link www.sonacms.net
  */
  
  
class SessionManager  
{
	public static $logged_in;
	public static $error_message;
	
	
	/* Register session data
	 *	Also update any data in there
	 */
	function RegisterData(&$info)
	{
		while(list($key, $val) = each($info))
		{
			$_SESSION[$key] = serialize($val);
		}
	}
	
	function AddData($key, $value)
	{
		/*if(is_array($value) || is_object($value))
			$_SESSION[$key] = serialize($value);
		else
			$_SESSION[$key] = $value;*/
			
		$_SESSION[$key] = serialize($value);
	}
	
	function GetData($key)
	{
		return unserialize($_SESSION[$key]);
	}
	
	function GetValue($key, $index)
	{
		$upack = unserialize($_SESSION[$key]);
		
		if(is_object($upack))
			return $upack->$index;
		else
			return $upack[$index];
			
	}
	
	function Logout()
	{
		session_destroy();
	}
}

?>