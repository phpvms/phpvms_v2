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
	public static function GetSchedule($id)
	{
		#$id = DB::escape($id);
		$sql = 'SELECT s.*, a.name as aircraft, a.registration
					FROM '. TABLE_PREFIX.'schedules s
						LEFT JOIN '.TABLE_PREFIX.'aircraft a ON a.id=s.aircraft
					WHERE s.id='.intval($id);
		
		return DB::get_row($sql);
	}
	
	public static function GetScheduleByFlight($code, $flightnum, $leg=1)
	{
		$sql = 'SELECT s.*, a.name as aircraft, a.registration,
							dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
							arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules s
						INNER JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						INNER JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
						INNER JOIN '.TABLE_PREFIX.'aircraft AS a ON a.id = s.aircraft
					WHERE s.code=\''.$code.'\' 
						AND s.flightnum=\''.$flightnum.'\'';
						
		if($leg != '')
		{
			$sql .= ' AND s.leg='.$leg;
		}
				
		return DB::get_row($sql);
	}
	
	public static function FindFlight($flightnum, $depicao='')
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'schedules
					WHERE flightnum=\''.$flightnum.'\' ';
					
		if($depicao != '')
		{
			$sql .= 'AND depicao=\''.$depicao.'\'';
		}
		
		return DB::get_row($sql);		
	}
	
	public static function IncrementFlownCount($schedid)
	{
		$schedid = intval($schedid);
		
		$sql = 'UPDATE '.TABLE_PREFIX.'schedules 
					SET timesflown=timesflown+1
					WHERE id='.$schedid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public static function GetScheduleDetailed($id)
	{
		$limit = DB::escape($limit);
		
		$sql = 'SELECT s.*, a.name as aircraft, a.registration,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules s
						LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
						LEFT JOIN '.TABLE_PREFIX.'aircraft AS a ON a.id = s.aircraft
					WHERE s.id='.$id;
		
		return DB::get_row($sql);
	}
	
	
	/**
	 * Return all schedules which have no distance value
	 */
	public static function GetSchedulesNoDistance()
	{
		$sql = 'SELECT s.*, a.name as aircraft, a.registration,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules s
						LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
						LEFT JOIN '.TABLE_PREFIX.'aircraft AS a ON a.id = s.aircraft
					WHERE s.distance=0';
		
		return DB::get_results($sql);
	}
	
	/**
	 * Return all the airports by depature, which have a schedule, for
	 *	a certain airline. If the airline
	 * @return object_array
	 */
	public static function GetDepartureAirports($airlinecode='', $onlyenabled=false)
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
	public static function GetArrivalAiports($depicao, $airlinecode='', $onlyenabled=true)
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
	public static function GetRoutesWithDeparture($depicao, $onlyenabled=true, $limit='')
	{
		$depicao = DB::escape($depicao);
		
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
			
		$sql = 'SELECT s.*, a.name as aircraft, a.registration,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules AS s
						LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
						LEFT JOIN '.TABLE_PREFIX.'aircraft AS a ON a.id = s.aircraft
					WHERE s.depicao=\''.$depicao.'\' '.$enabled;
		
		return DB::get_results($sql);
	}
	
	public static function GetRoutesWithArrival($arricao, $onlyenabled=true, $limit='')
	{
		$arricao = DB::escape($arricao);
		
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
			
		$sql = 'SELECT s.*, a.name as aircraft, a.registration,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules s
						LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
						LEFT JOIN '.TABLE_PREFIX.'aircraft AS a ON a.id = s.aircraft
					WHERE s.arricao=\''.$arricao.'\' '.$enabled;
		
		return DB::get_results($sql);
		DB::debug();
		return $ret;
	}
	
	public static function GetSchedulesByDistance($distance, $type, $onlyenabled=true, $limit='')
	{
		$distance = DB::escape($distance);
		$limit = DB::escape($limit);
		
		if($type == '')
			$type = '>';
			
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
		
		$sql = 'SELECT s.*, a.name as aircraft, a.registration,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules AS s
						LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
						LEFT JOIN '.TABLE_PREFIX.'aircraft AS a ON a.id = s.aircraft
					WHERE s.distance '.$type.' '.$distance.' '.$enabled.'
						ORDER BY s.depicao DESC';
	
		if($limit != '')
			$sql .= ' LIMIT ' . $limit;
		
		return DB::get_results($sql);
	}
	
	/**
	 * Search schedules by the equipment type
	 */
	public static function GetSchedulesByEquip($ac, $onlyenabled = true, $limit='')
	{
		$ac = DB::escape($ac);
		$limit = DB::escape($limit);
		
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
		
		$sql = 'SELECT s.*, a.name as aircraft, a.registration
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'aircraft a
					WHERE a.name=\''.$ac.'\' AND a.id=s.aircraft
					'.$enabled.'
					ORDER BY s.depicao DESC';
		
		if($limit != '')
			$sql .= ' LIMIT ' . $limit;
		
		$ret = DB::get_results($sql);
		//DB::debug();
		return $ret;
	}
	
	/**
	 * Get all the schedules, $limit is the number to return
	 */
	public static function GetSchedules($limit='', $onlyenabled=true)
	{
		
		$limit = DB::escape($limit);
		
		if($onlyenabled)
			$enabled = 'WHERE s.enabled=1';
		else
			$enabled = '';
		
		$sql = 'SELECT s.*, a.name as aircraft, a.registration,
						dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
						arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
					FROM '.TABLE_PREFIX.'schedules AS s
						LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
						LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
						LEFT JOIN '.TABLE_PREFIX.'aircraft AS a ON a.id = s.aircraft
					'.$enabled.'
					ORDER BY s.depicao DESC';
		
		if($limit != '')
			$sql .= ' LIMIT ' . $limit;
		
		$ret =  DB::get_results($sql);
		
		return $ret;
		
	}
	
	/**
	 * This gets all of the schedules which are disabled
	 */
	public static function GetInactiveSchedules()
	{
		$sql = 'SELECT s.*, a.name as aircraft, a.registration
					FROM '.TABLE_PREFIX.'schedules s
						LEFT JOIN '.TABLE_PREFIX.'aircraft a ON a.id=s.aircraft
					WHERE s.enabled=0
					ORDER BY s.flightnum ASC';
					
		return DB::get_results($sql);
		
		DB::debug();
		return $ret;
	}
	
	
	/**
	 * Calculate the distance between two coordinates
	 * Using a revised equation found on http://www.movable-type.co.uk/scripts/latlong.html
	 * 
	 * Also converts to proper type based on UNIT setting
	 */
	public static function distanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
	{
		
		$distance = (3958 * 3.1415926 * sqrt(($lat2-$lat1) * ($lat2-$lat1) 
					+ cos($lat2/57.29578) * cos($lat1/57.29578) * ($lon2-$lon1) * ($lon2-$lon1))/180);
		
		# Distance is in miles
		#	Do proper conversions if needed
		#	Return in nm by default
		
		if(strtolower(Config::Get('UNITS')) == 'mi')
		{
			# Leave it in miles
			return $distance;
		}
		elseif(strtolower(Config::Get('UNITS')) == 'km')
		{
			# Convert to km
			return $distance * 1.609344;
		}
		else
		{
			# Convert to nm
			return $distance * .868976242; # Convert to nautical miles
		}

		return round($distance, 2);
		
		# My old one... hahaha
		/*$radius = 3963; #miles
		$a1=deg2rad($lat1);
		$a2=deg2rad($lat2);
		
		$b1=deg2rad($lng1);
		$b2=deg2rad($lng2);
		
		$theta_lat = ($a1-$a2);
		$theta_lng = ($b1-$b2);
		
		$top1 = pow(cos($a2)*sin($theta_lng), 2);
		$top2 = pow((cos($a1)*sin($a2)) - (sin($a1)*cos($a2)*cos($theta_lng)), 2);
		$bottom = (sin($a1)*sin($a2)) + (cos($a1)*cos($a2)*cos($theta_lng));
		
		$haversine = atan(sqrt($top1+$top2)/$bottom);
		$distance = $haversine * $radius;
		
		return round($distance, 2);*/
	}
	
	/**
	 * Update a distance
	 */
	public static function UpdateDistance($scheduleid, $distance)
	{
		$sql = 'UPDATE '.TABLE_PREFIX.'schedules 
					SET distance=\''.$distance.'\'
					WHERE id='.$scheduleid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
	
	/**
	 * Add a schedule
	 * 
	 * Pass in the following:
				$data = array(	'code'=>'',
						'flightnum'='',
						'leg'=>'',
						'depicao'=>'',
						'arricao'=>'',
						'route'=>'',
						'aircraft'=>'',
						'distance'=>'',
						'deptime'=>'',
						'arrtime'=>'',
						'flighttime'=>'',
						'notes'=>'',
						'enabled'=>'',
						'maxload'=>'',
						'price'=>''
						'flighttype'=>'');
	 */
	public static function AddSchedule($data)
	{
	
		if(!is_array($data))
			return false;
						
		if($data['depicao'] == $data['arricao'])
			return false;
		
		if($data['leg'] == '' || $data['leg'] == '0')
			$data['leg'] = 1;
			
		$data['deptime'] = strtoupper($data['deptime']);
		$data['arrtime'] = strtoupper($data['arrtime']);
		
		if($data['enabled'] == true)
			$data['enabled'] = true;
		else
			$data['enabled'] = false;
		
		# If they didn't specify 
		$data['flighttype'] = strtoupper($data['flighttype']);
		if($data['flighttype'] != 'P' && $data['flighttype'] != 'C')
			$data['flighttype'] = 'P';
			
		foreach($data as $key=>$value)
		{
			$data[$key] = DB::escape($value);
		}
				
		$sql = "INSERT INTO " . TABLE_PREFIX ."schedules
						(`code`, `flightnum`, `leg`, 
						 `depicao`, `arricao`, 
						 `route`, `aircraft`, `distance`, 
						 `deptime`, `arrtime`, 
						 `flighttime`, `maxload`, `price`, 
						 `flighttype`, `notes`, `enabled`)
					VALUES ('$data[code]', 
							'$data[flightnum]', 
							'$data[leg]', 
							'$data[depicao]', 
							'$data[arricao]', 
							'$data[route]', 
							'$data[aircraft]', 
							'$data[distance]',
							'$data[deptime]', 
							'$data[arrtime]',
							'$data[flighttime]',
							'$data[maxload]',
							'$data[price]', 
							'$data[flighttype]',
							'$data[notes]', 
							$data[enabled])";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}

	/**
	 * Edit a schedule
	 * Pass in the following:
		
			$data = array(	'scheduleid'=>'',
							'code'=>'',
							'flightnum'='',
							'leg'=>'',
							'depicao'=>'',
							'arricao'=>'',
							'route'=>'',
							'aircraft'=>'',
							'distance'=>'',
							'deptime'=>'',
							'arrtime'=>'',
							'flighttime'=>'',
							'notes'=>'',
							'enabled'=>'',
							'maxload'=>'',
							'price'=>'',
							'flighttype'=>'P' OR 'C');
	 */
	public static function EditSchedule($data)
	{
		if(!is_array($data))
			return false;
		
		if($data['depicao'] == $data['arricao'])
			return false;
		
		if($data['leg'] == '' || $data['leg'] == '0')
			$data['leg'] = 1;
		
		$data['deptime'] = strtoupper($data['deptime']);
		$data['arrtime'] = strtoupper($data['arrtime']);
		
		if($data['enabled'] == true)
			$data['enabled'] = true;
		else
			$data['enabled'] = false;
			
		# If they didn't specify a flight type, just default to pax
		$data['flighttype'] = strtoupper($data['flighttype']);
		if($data['flighttype'] != 'P' && $data['flighttype'] != 'C')
			$data['flighttype'] = 'P';
		
		foreach($data as $key=>$value)
		{
			$data[$key] = DB::escape($value);
		}
			
		$sql = "UPDATE " . TABLE_PREFIX ."schedules 
					SET `code`='$data[code]', 
						`flightnum`='$data[flightnum]', 
						`leg`='$data[leg]',
						`depicao`='$data[depicao]', 
						`arricao`='$data[arricao]',
						`route`='$data[route]', 
						`aircraft`='$data[aircraft]', 
						`distance`='$data[distance]', 
						`deptime`='$data[deptime]',
						`arrtime`='$data[arrtime]', 
						`flighttime`='$data[flighttime]', 
						`maxload`='$data[maxload]',
						`price`='$data[price]',
						`flighttype`='$data[flighttype]',
						`notes`='$data[notes]', 
						`enabled`=$data[enabled]
					WHERE `id`=$data[scheduleid]";

		$res = DB::query($sql);
				
		if(DB::errno() != 0)
			return false;
			
		return true;
	}

	/**
	 * Delete a schedule
	 */
	public static function DeleteSchedule($scheduleid)
	{
		$scheduleid = DB::escape($scheduleid);
		$sql = 'DELETE FROM ' .TABLE_PREFIX.'schedules 
					WHERE id='.$scheduleid;

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Get the latest bids
	 */
	 
	public static function GetLatestBids($limit=5)
	{
		$sql = 'SELECT s.*, b.bidid, a.name as aircraft, a.registration
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'bids b,
						'.TABLE_PREFIX.'aircraft a
					WHERE b.routeid = s.id 
							AND s.aircraft=a.id
					ORDER BY bidid DESC
					LIMIT '.$limit;
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get a specific bid with route information
	 *
	 * @param unknown_type $bidid
	 * @return unknown
	 */
	public static function GetBid($bidid)
	{
		$bidid = DB::escape($bidid);
		$sql = 'SELECT s.*, b.bidid, a.name as aircraft, a.registration
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'bids b,
						'.TABLE_PREFIX.'aircraft a
					WHERE b.routeid = s.id 
							AND s.aircraft=a.id
							AND b.bidid='.$bidid;
		
		return DB::get_row($sql);
	}
	
	/**
	 * Get all of the bids for a pilot
	 *
	 * @param unknown_type $pilotid
	 * @return unknown
	 */
	public static function GetBids($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		$sql = 'SELECT s.*, b.bidid, a.name as aircraft, a.registration
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'bids b,
						'.TABLE_PREFIX.'aircraft a
					WHERE b.routeid = s.id 
						AND s.aircraft=a.id
						AND b.pilotid='.$pilotid;
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get find a bid for the pilot based on ID,
	 *	the airline code for the flight, and the flight number
	 */
	public static function GetBidWithRoute($pilotid, $code, $flightnum)
	{
		$sql = 'SELECT b.bidid 
					FROM '.TABLE_PREFIX.'bids b, '.TABLE_PREFIX.'schedules s
					WHERE b.pilotid='.$pilotid.' AND b.routeid=s.id
						AND s.code=\''.$code.'\' AND s.flightnum=\''.$flightnum.'\'';
		
		return DB::get_row($sql);
	}
	
	
	/**
	 * Add a bid
	 */		
	public static function AddBid($pilotid, $routeid)
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
	
	/**
	 * Remove a bid, by passing it's bid id
	 */
	public static function RemoveBid($bidid)
	{
		$bidid = DB::escape($bidid);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'bids WHERE bidid='.$bidid;
		
		DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public static function GetScheduleFlownCounts($code, $flightnum, $days=30)
	{
		$max = 0;
		
		$time_start = strtotime("- $days days");
		$time_end = time();
		$data = array();
				
		# Turn on caching:		
		DB::enableCache();
			
		do 
		{	
			$count = PIREPData::GetReportCountForRoute($code, $flightnum, $time_start);
			$data[] = $count;			
			
			if($count > $max)
				$max = $count;
				
			$timestart += SECONDS_PER_DAY;
			
		}  while ($time_start < $time_end);
		
		DB::disableCache();
		
		return $data;
	}
	
	/**
	 * Show the graph of the past week's reports. Outputs the
	 *	image unless $ret == true
	 */
	public static function ShowReportCounts()
	{
		// Recent PIREP #'s
		$max = 0;
		$data = array();

		$time_start = strtotime('-7 days');
		$time_end = time();
		
		// This is for the past 7 days
		do {
			$count = PIREPData::GetReportCount($time_start);

			//array_push($data, intval($count));
			//$label .= date('m/d', $date) .'|';
			$data[] = $count;
			
			if($count > $max)
				$max = $count;
				
			$time_start += SECONDS_PER_DAY;
		} while ($time_start < $time_end);
			
		return $data;
	}
}