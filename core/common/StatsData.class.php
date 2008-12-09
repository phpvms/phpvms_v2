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
 
class StatsData
{
	
	/**
	 * Get the total number of hours flown by pilots
	 */
	public static function TotalHours()
	{
		$sql = 'SELECT SUM(totalhours) AS total FROM '.TABLE_PREFIX.'pilots';
		$res = DB::get_row($sql);
		return $res->total;
	}
	
	/**
	 * Get the total number of flights flown
	 */
	public static function TotalFlights()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.TABLE_PREFIX.'pireps';
		$res = DB::get_row($sql);
		return $res->total;
	}
	
	/**
	 * Get the total number of pilots
	 */
	public static function PilotCount()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.TABLE_PREFIX.'pilots';
		$res = DB::get_row($sql);
		return $res->total;
	}
	
	/**
	 * Get the current aircraft usage
	 */
	public static function AircraftUsage()
	{
		$sql = 'SELECT a.name AS aircraft, a.registration, 
						COUNT(p.flighttime) AS hours, COUNT(p.distance) AS distance
					FROM '.TABLE_PREFIX.'pireps p
						INNER JOIN '.TABLE_PREFIX.'aircraft a ON p.aircraft = a.id
					GROUP BY p.aircraft';
		
		return DB::get_results($sql);
		DB::debug();
		return $ret;
	}
	
	/**
	 * Show pie chart for all of the aircraft flown
	 *  by a certain pilot. Outputs image, unless $ret == true,
	 * 	then it returns the URL.
	 */
	public static function AircraftFlownGraph($ret = false)
	{
		//Select aircraft types
		$sql = 'SELECT a.name AS aircraft, COUNT(p.aircraft) AS count
					FROM '.TABLE_PREFIX.'pireps p, '.TABLE_PREFIX.'aircraft a 
					WHERE p.aircraft = a.id
					GROUP BY a.name';
		
		$stats = DB::get_results($sql);
		
		if(!$stats)
		{
			return;
		}
		
		$data = '';
		$labels = '';
		foreach($stats as $stat)
		{
			if($stat->aircraft == '') continue;
			
			$data .= $stat->count . ',';
			$labels .= $stat->aircraft.'|';
		}
		
		// remove that final lone char
		$data = substr($data, 0, strlen($data)-1);
		$labels = substr($labels, 0, strlen($labels)-1);
		
		$chart = new googleChart($data, 'pie');
		$chart->dimensions = '350x200';
		$chart->setLabels($labels);
		
		if($ret == true)
			return $chart->draw(false);
		else
			echo '<img src="'.$chart->draw(false).'" />';
	}
	
	/**
	 * Show pie chart for all of the aircraft flown
	 *  by a certain pilot. Outputs image, unless $ret == true,
	 * 	then it returns the URL.
	 */
	public static function PilotAircraftFlownGraph($pilotid, $ret = false)
	{
		//Select aircraft types
		$sql = 'SELECT a.name AS aircraft, COUNT(p.aircraft) AS count
					FROM '.TABLE_PREFIX.'pireps p, '.TABLE_PREFIX.'aircraft a 
					WHERE p.aircraft = a.id AND pilotid='.intval($pilotid).'
					GROUP BY a.name';
		
		$stats = DB::get_results($sql);

		if(!$stats)
		{
			return;
		}
		
		$data = '';
		$labels = '';
		foreach($stats as $stat)
		{
			if($stat->aircraft == '') continue;

			$data .= $stat->count . ',';
			$labels .= $stat->aircraft.'|';
		}

		// remove that final lone char
		$data = substr($data, 0, strlen($data)-1);
		$labels = substr($labels, 0, strlen($labels)-1);

		$chart = new googleChart($data, 'pie');
		$chart->dimensions = '350x200';
		$chart->setLabels($labels);

		if($ret == true)
			return $chart->draw(false);
		else
			echo '<img src="'.$chart->draw(false).'" />';
	}
}
?>