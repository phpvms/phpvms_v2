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
 
class SchedulesData
{

	/**
	 * Return information about a schedule (pass the ID)
	 */
	public function GetSchedule($id)
	{
		#$id = DB::escape($id);
		$sql = 'SELECT * FROM '. TABLE_PREFIX.'schedules WHERE id='.intval($id);
		
		return DB::get_row($sql);
	}
	
	public function GetScheduleByFlight($code, $flightnum, $leg=1)
	{
		if($leg == '') $leg = 1;
		
		$sql = 'SELECT s.*, dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
							arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules AS s
						INNER JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						INNER JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
					WHERE s.code=\''.$code.'\' 
						AND s.flightnum=\''.$flightnum.'\'
						AND s.leg='.$leg;
						
		//$sql = "SELECT * FROM phpvms_schedules WHERE code='$code' AND flightnum='$flightnum' AND leg='$leg'";
		
		return DB::get_row($sql);
	}
	
	public function IncrementFlownCount($code, $flightnum)
	{
		$sql = 'UPDATE '.TABLE_PREFIX.'schedules SET timesflown=timesflown+1
					WHERE code=\''.$code.'\' AND flightnum=\''.$flightnum.'\'';
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public function GetScheduleDetailed($id)
	{
		$limit = DB::escape($limit);
		
		$sql = 'SELECT s.*, dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
							arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules AS s
						INNER JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						INNER JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
					WHERE s.id='.$id;
		
		return DB::get_row($sql);
	}
	
	/**
	 * Return all the airports by depature, which have a schedule, for
	 *	a certain airline. If the airline
	 * @return object_array
	 */
	public function GetDepartureAirports($airlinecode='', $onlyenabled=false)
	{
		$airlinecode = DB::escape($airlinecode);
		
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
		
		$sql = 'SELECT DISTINCT s.depicao AS icao, a.name
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'airports a
					WHERE s.depicao = a.icao '.$enabled;
					
		if($airlinecode != '')
			$sql .= ' AND s.code=\''.$airlinecode.'\' ';
			
		$sql .= ' ORDER BY depicao ASC';
									
		return DB::get_results($sql);
	}
	
	/**
	 * Get all of the airports which have a schedule, from
	 *	a certain airport, using the airline code. Code
	 *	is optional, otherwise it returns all of the airports.
	 * @return database object
	 */
	public function GetArrivalAiports($depicao, $airlinecode='', $onlyenabled=true)
	{
		$depicao = DB::escape($depicao);
		
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
		
		$sql = 'SELECT DISTINCT s.arricao AS icao, a.name
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'airports a
					WHERE s.arricao = a.icao '.$enabled;

		if($airlinecode != '')
			$sql .= ' AND s.code=\''.$airlinecode.'\' ';
		
		$sql .= ' ORDER BY depicao ASC';
		
		return DB::get_results($sql);
	}
	
	/**
	 * Return all of the routes give the departure airport
	 */
	public function GetRoutesWithDeparture($depicao, $onlyenabled=true, $limit='')
	{
		$depicao = DB::escape($depicao);
		
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
			
		$sql = 'SELECT s.*, dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
							arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules AS s
						INNER JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						INNER JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
					WHERE s.depicao=\''.$depicao.'\' '.$enabled;
		
		return DB::get_results($sql);
	}
	
	public function GetRoutesWithArrival($arricao, $onlyenabled=true, $limit='')
	{
		$arricao = DB::escape($arricao);
		
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
			
		$sql = 'SELECT s.*, dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
							arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules AS s
						INNER JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						INNER JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
					WHERE s.arricao=\''.$arricao.'\' '.$enabled;
		
		return DB::get_results($sql);
	}
	
	public function GetSchedulesByDistance($distance, $type, $onlyenabled=true, $limit='')
	{
		$distance = DB::escape($distance);
		$limit = DB::escape($limit);
		
		if($type == '')
			$type = '>';
			
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
		
		$sql = 'SELECT s.*, dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
							arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules AS s
						INNER JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						INNER JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
					WHERE s.distance '.$type.' '.$distance.' '.$enabled.'
						ORDER BY s.depicao DESC';
	
		if($limit != '')
			$sql .= ' LIMIT ' . $limit;
		
		return DB::get_results($sql);
	}
	
	/**
	 * Search schedules by the equipment type
	 */
	public function GetSchedulesByEquip($ac, $onlyenabled = true, $limit='')
	{
		$ac = DB::escape($ac);
		$limit = DB::escape($limit);
		
		if($onlyenabled)
			$enabled = 'AND enabled=1';
		else
			$enabled = '';
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'schedules
					WHERE aircraft = \''.$ac.'\' '.$enabled.'
					ORDER BY depicao DESC';
		
		if($limit != '')
			$sql .= ' LIMIT ' . $limit;
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get all the schedules, $limit is the number to return
	 */
	public function GetSchedules($limit='', $onlyenabled=true)
	{
		
		$limit = DB::escape($limit);
		
		if($onlyenabled)
			$enabled = 'WHERE s.enabled=1';
		else
			$enabled = '';
		
		$sql = 'SELECT s.*, dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
							arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules AS s
						INNER JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						INNER JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
					'.$enabled.'
					ORDER BY s.depicao DESC';
		
		if($limit != '')
			$sql .= ' LIMIT ' . $limit;
		
		return DB::get_results($sql);
	}
	
	/**
	 * Add a schedule
	 */
	public function AddSchedule($code, $flightnum, $leg, $depicao, $arricao, $route,
				$aircraft, $distance, $deptime, $arrtime, $flighttime, $notes='', $enabled = true)
	{
		$code = DB::escape($code);
		$flightnum = DB::escape($flightnum);
		$leg = DB::escape($leg);
		$depicao = DB::escape($depicao);
		$arricao = DB::escape($arricao);
		$route = DB::escape($route);
		$aircraft = DB::escape($aircraft);
		$distance = DB::escape($distance);
		$deptime = DB::escape($deptime);
		$arrtime = DB::escape($arrtime);
		$flighttime = DB::escape($flighttime);
		$notes = DB::escape($notes);
		
		if($leg == '') $leg = 1;
		$deptime = strtoupper($deptime);
		$arrtime = strtoupper($arrtime);
		
		if($depicao == $arricao) return false;
		if(self::GetScheduleByFlight($code,$flightnum, $leg)) return false; // flight with same num/code already exists
		
		
		if($enabled == true)
			$enabled = 1;
		else
			$enabled = 0;
		
		$sql = "INSERT INTO " . TABLE_PREFIX ."schedules
				(code, flightnum, leg, depicao, arricao, route, aircraft, distance, deptime, arrtime, flighttime, notes, enabled)
				VALUES ('$code', '$flightnum', '$leg', '$depicao', '$arricao', '$route', '$aircraft', '$distance',
				'$deptime', '$arrtime', '$flighttime', '$notes', $enabled)";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}

	/**
	 * Edit a schedule
	 */
	public function EditSchedule($scheduleid, $code, $flightnum, $leg, $depicao, $arricao, $route,
				$aircraft, $distance, $deptime, $arrtime, $flighttime, $notes='', $enabled=true)
	{

		$scheduleid = DB::escape($scheduleid);
		$code = DB::escape($code);
		$flightnum = DB::escape($flightnum);
		$leg = DB::escape($leg);
		$depicao = DB::escape($depicao);
		$arricao = DB::escape($arricao);
		$route = DB::escape($route);
		$aircraft = DB::escape($aircraft);
		$distance = DB::escape($distance);
		$deptime = DB::escape($deptime);
		$arrtime = DB::escape($arrtime);
		$flighttime = DB::escape($flighttime);
		$notes = DB::escape($notes);
		
		if($leg == '') $leg = 1;
		$deptime = strtoupper($deptime);
		$arrtime = strtoupper($arrtime);

		if($enabled == true)
			$enabled = 1;
		else
			$enabled = 0;
			
		$sql = "UPDATE " . TABLE_PREFIX ."schedules SET code='$code', flightnum='$flightnum', leg='$leg',
						depicao='$depicao', arricao='$arricao',
						route='$route', aircraft='$aircraft', distance='$distance', deptime='$deptime',
						arrtime='$arrtime', flighttime='$flighttime', notes='$notes', enabled=$enabled
					WHERE id=$scheduleid";

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}

	/**
	 * Delete a schedule
	 */
	public function DeleteSchedule($scheduleid)
	{
		$scheduleid = DB::escape($scheduleid);
		$sql = 'DELETE FROM ' .TABLE_PREFIX.'schedules WHERE id='.$scheduleid;

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	
	/**
	 * Get a specific bid with route information
	 *
	 * @param unknown_type $bidid
	 * @return unknown
	 */
	public function GetBid($bidid)
	{
		$bidid = DB::escape($bidid);
		$sql = 'SELECT s.*, b.bidid
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'bids b
					WHERE b.routeid = s.id AND b.bidid='.$bidid;
		
		return DB::get_row($sql);
	}
	
	/**
	 * Get all of the bids for a pilot
	 *
	 * @param unknown_type $pilotid
	 * @return unknown
	 */
	public function GetBids($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		$sql = 'SELECT s.*, b.bidid
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'bids b
					WHERE b.routeid = s.id AND b.pilotid='.$pilotid;
		
		return DB::get_results($sql);
	}
		
	public function AddBid($pilotid, $routeid)
	{
		$pilotid = DB::escape($pilotid);
		$routeid = DB::escape($routeid);
		
		if(DB::get_row('SELECT bidid FROM '.TABLE_PREFIX.'bids
				WHERE pilotid='.$pilotid.' AND routeid='.$routeid))
		{
			return;
		}
			
		$pilotid = DB::escape($pilotid);
		$routeid = DB::escape($routeid);
		
		$sql = 'INSERT INTO '.TABLE_PREFIX.'bids (pilotid, routeid)
					VALUES ('.$pilotid.', '.$routeid.')';
		
		DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public function RemoveBid($bidid)
	{
		$bidid = DB::escape($bidid);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'bids WHERE bidid='.$bidid;
		
		DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public function GetScheduleFlownCounts($code, $flightnum, $days=30)
	{
		$max = 0;
		$data = '[';
		//$days = $days * -1;

		// turn on cacheing:
		
		DB::enableCache();
		// This is for the past 7 days
		for($i=-30;$i<=0;$i++)
		{
			$date = mktime(0,0,0,date('m'), date('d') + $i ,date('Y'));
			$count = PIREPData::GetReportCountForRoute($code, $flightnum, $date);
			//DB::debug();

			//array_push($data, intval($count));
			//$label .= date('m/d', $date) .'|';
			$data.=$count.',';
			if($count > $max)
				$max = $count;
		}
		
		DB::disableCache();
		
		$data = substr($data, 0, strlen($data)-1);
		$data .= ']';
		
		return $data;
	}
	
	/**
	 * Show the graph of the past week's reports. Outputs the
	 *	image unless $ret == true
	 */
	public function ShowReportCounts()
	{
		// Recent PIREP #'s
		$max = 0;
		$data = '[';

		// This is for the past 7 days
		for($i=-7;$i<=0;$i++)
		{
			$date = mktime(0,0,0,date('m'), date('d') + $i ,date('Y'));
			$count = PIREPData::GetReportCount($date);

			//array_push($data, intval($count));
			//$label .= date('m/d', $date) .'|';
			$data.=$count.',';
			if($count > $max)
				$max = $count;
		}
		
		$data = substr($data, 0, strlen($data)-1);
		$data .= ']';
		
		return $data;
	}
}
?>