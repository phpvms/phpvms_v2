<?php


class Config
{
	static $values = array();
	
	public static function Add($name, $value)
	{
		/*if(!$values)
			$values = array();*/
			
		self::$values[$name] = $value;
	}
	
	public static function Append($name, $key='', $value)
	{
		if(is_array(self::$values[$name]) == true)
		{
			if($key == '')
				self::$values[$name][] = $value;
			else
				self::$values[$name][$key] = $value;
		}
	}
	
	public static function GetType($name)
	{
		if(is_array(self::$values[$name]))
			return 'array';
		elseif(is_object(self::$values[$name]))
			return 'object';
		elseif(is_float(self::$values[$name]))
			return 'float';
		elseif(is_int(self::$values[$name]))
			return 'int';
		else
			return 'string';
	}
	
	public static function Get($name, $key='')
	{
		if($key != '')
		{
			if(is_array(self::$values[$name]))
				return self::$values[$name][$key];
			elseif(is_object(self::$values[$name]))
				return self::$values[$name]->$key;
		}
		
		return self::$values[$name];
	}
	
	public static function Remove($name, $key='')
	{
		if($key != '')
			unset(self::$values[$name][$key]);
		else
			unset(self::$values[$name]);
	}
	
	/**
	 * Load all the site settings. Make the settings into define()'s
	 *	so they're accessible from everywhere
	 */
	public static function LoadSettings()
	{
		while(list($key, $value) = each(self::$values))
		{
			if(!is_array($value))
				define($key, $value);
		}
			
		return true;
	}
}
?>