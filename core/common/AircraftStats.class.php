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
 */

class AircraftStats
{
	
	
	/**
	 * Return summary/detailed information about all aircraft
	 *
	 * @return array Array of objects with the flight data
	 *
	 */
	public static function getAircraftDetails()
	{
		$sql = 'SELECT a.*,
					   SUM(p.distance) AS totaldistance,
					   SUM(p.flighttime) AS totaltime,
					   AVG(p.distance) AS averagedistance,
					   AVG(p.flighttime) as averagetime
				  FROM   '.TABLE_PREFIX.'pireps p
					INNER JOIN '.TABLE_PREFIX.'aircraft a
						ON (p.aircraft = a.registration)
				  GROUP BY a.registration';
		
		return DB::get_results($sql);       
    }
}