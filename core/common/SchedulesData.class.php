<?php
/**
 * SchedulesData
 *
 * Database model for any data related to schedules
 * 
 * @author Nabeel Shahzad <contact@phpvms.net>
 * @copyright Copyright (c) 2008, phpVMS Project
 * @license http://www.phpvms.net/license.php
 */

class SchedulesData
{

	function GetDepartureAirports()
	{	
		$sql = 'SELECT DISTINCT s.depicao, a.name 
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'airports a
					WHERE s.depicao = a.icao 
					ORDER BY depicao ASC';
									
		$ret = DB::get_results($sql);		
		return $ret;
	}
	
	function GetRoutesWithDeparture($depicao)
	{
		$sql = 'SELECT * from '.TABLE_PREFIX.'schedules WHERE depicao=\''.$depicao.'\'';
		
		return DB::get_results($sql);		
	}
}

?>