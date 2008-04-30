<?php
/*
Codon PHP Framework
www.nsslive.net/codon

 Software License Agreement (BSD License)
 
 Copyright (c) 2008 Nabeel Shahzad, nsslive.net

 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions
 are met:

 1. Redistributions of source code must retain the above copyright
    notice, this list of conditions and the following disclaimer.
 2.  Redistributions in binary form must reproduce the above copyright
    notice, this list of conditions and the following disclaimer in the
    documentation and/or other materials provided with the distribution.
 3. The name of the author may not be used to endorse or promote products
    derived from this software without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
 * LiveFrame - www.nsslive.net
 *	
 * Vars
 *	Handles global variables
 * 
 * revision updates:
 *	0 - Added
 */
 
class Vars
{
	public static $getcount=null;
	public static $postcount=null;
	
	public static function cleaned(&$V)
	{
		return htmlspecialchars(addslashes(stripslashes($V)));
	}	
	
	public static function Request($name)
	{
		return self::cleaned($_REQUEST[$name]);
	}
	
	public static function POST($name)
	{
		if(is_array($_POST[$name]))
		{
			return $_POST[$name];
		}
		
		return self::cleaned($_POST[$name]);
	}
	
	public static function GET($name)
	{
		if(is_array($_GET[$name]))
		{
			return $_GET[$name];
		}
		
		return self::cleaned($_GET[$name]);
	}
	
	public static function POST_COUNT()
	{
		if(self::$postcount == null)
			self::$postcount = count($_POST);
		
		return self::$postcount;
	}
	
	public static function GET_COUNT()
	{
		if(self::$getcount == null)
			self::$getcount = count($_GET);
		
		return self::$getcount;
	}
}
?>