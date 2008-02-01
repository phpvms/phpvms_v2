<?php

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
		return self::cleaned($_POST[$name]);
	}
	
	public static function GET($name)
	{
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