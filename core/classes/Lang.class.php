<?php


class Lang 
{
	public static $language;
	public static $trans_table;
	
	public static function set_language($lang='en')
	{
		self::$language = $lang;
		
		$path = CORE_PATH.DS.'lang'.DS.$lang.'.lang.php';
		if(!file_exists($path)){
			return false;
		}
		
		include $path;
		
		/* Load up the translation table from the /lang/[lang]/lang.php file */
		self::$trans_table = $trans;
		unset($trans);
	}	
	
	
	public static function gs($string)
	{
		return self::$trans_table[$string];
	}
	
	/* Alias */
	public static function get($string)
	{
		return self::gs($string);
	}
	
}