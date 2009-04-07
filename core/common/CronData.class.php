<?php

class CronData
{
	
	/**
	 * Checks the last update time for a given event
	 * Returns the difference in days and in time
	 *
	 */
	public static function check_lastupdate($name)
	{
		$name = strtoupper($name);
		$sql = 'SELECT *, DATEDIFF(NOW(), lastupdate) AS days,
						  TIMEDIFF(NOW(), lastupdate) as timediff,
				 FROM '.TABLE_PREFIX."updates
				 WHERE name='{$name}'";
				 
		return DB::get_row($sql);
	}

	
	/**
	 * Sets the last update time for an event to NOW()
	 *
	 */
	public static function set_lastupdate($name)
	{
		$name = strtoupper($name);
		if(!self::check_lastupdate($name))
		{
			$sql = "INSERT INTO ".TABLE_PREFIX."updates
							(name, lastupdate)
					VALUES	('{$name}', NOW())";
		}
		else
		{
			$sql = "UPDATE ".TABLE_PREFIX."updates
						SET lastupdate=NOW()
						WHERE name='{$name}'";
		}
		
		DB::query($sql);
	}
}