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
 
class SchedulesData extends CodonData
{	

/**
	 * A generic find function for schedules. As parameters, do:
	 * 
	 * $params = array( 's.depicao' => 'value',
	 *					's.arricao' => array ('multiple', 'values'),
	 *	);
	 * 
	 * Syntax is ('s.columnname' => 'value'), where value can be
	 *	an array is multiple values, or with a SQL wildcard (%) 
	 *  if that's what is desired.
	 * 
	 * Columns from the schedules table should be prefixed by 's.',
	 * the aircraft table as 'a.'
	 * 
	 * You can also pass offsets ($start and $count) in order to 
	 * facilitate pagination
	 * 
	 * @tutorial http://docs.phpvms.net/media/development/searching_and_retriving_schedules
	 */
	public static function findSchedules($params, $count = '', $start = '')
	{
		$sql = 'SELECT s.*, a.id as aircraftid, a.name as aircraft, a.registration,
					dep.name as depname, dep.lat AS deplat, dep.lng AS deplong,
					arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong
				FROM '.TABLE_PREFIX.'schedules AS s
					LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = s.depicao
					LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = s.arricao
					LEFT JOIN '.TABLE_PREFIX.'aircraft AS a ON a.id = s.aircraft ';
	
		/* Build the select "WHERE" based on the columns passed, this is a generic function */
		$sql .= DB::build_where($params);
		
		// Order matters
		if(Config::Get('SCHEDULES_ORDER_BY') != '')
		{
			$sql .= ' ORDER BY '.Config::Get('SCHEDULES_ORDER_BY');
		}
		
		if(strlen($count) != 0)
		{
			$sql .= ' LIMIT '.$count;
		}
		
		if(strlen($start) != 0)
		{
			$sql .= ' OFFSET '. $start;
		}
		
		$ret = DB::get_results($sql);
		return $ret;
	}
	
	/**
	 * Return information about a schedule (pass the ID)
	 */
	public static function getSchedule($id)
	{
		$schedules = self::findSchedules(array('s.id'=>$id));
		
		if(!$schedules)
			return false;
			
		return $schedules[0];
	}
	
	
	/**
	 * Return a flight given the airline code and flight number
	 *
	 * @deprecated
	 * 
	 * @param string $code Airline code
	 * @param mixed $flightnum Flight number
	 * @return array Returns a full flight
	 *
	 */
	public static function getScheduleByFlight($code, $flightnum)
	{
		$params = array(
			's.code' => strtoupper($code),
			's.flightnum' => strtoupper($flightnum),
		);
		
		$schedule = self::findSchedules($params);
		return $schedule[0];
	}
		
	
	/**
	 * Find a flight on the flightnumber and departure airport
	 *
	 * @param string $flightnum Flight numbers
	 * @param string $depicao Departure airport
	 * @return array Returns one flight
	 *
	 */
	public static function findFlight($flightnum, $depicao='')
	{
		$params = array('s.flightnum' => strtoupper($flightnum));
		
		if($depicao != '')
		{
			$params['s.depicao'] = $depicao;
		}
		
		$schedule = self::findSchedules($params);
		return $schedule[0];
	}
	
	/**
	 * Extract the code and flight number portions from the flight number
	 * Ensures that the code and number are properly split
	 */
	public static function getProperFlightNum($flightnum)
	{
		if($flightnum == '')
			return false;
			
		$ret = array();	
		$flightnum = strtoupper($flightnum);
		$airlines = OperationsData::getAllAirlines(false);
				
		foreach($airlines as $a)
		{
			$a->code = strtoupper($a->code);
			
			if(strpos($flightnum, $a->code) === false)
			{
				continue;
			}
			
			$ret['code'] = $a->code;
			$ret['flightnum'] = str_ireplace($a->code, '', $flightnum);
			
			return $ret;
		}
		
		# Invalid flight number
		$ret['code'] = '';
		$ret['flightnum'] = $flightnum;
		return $ret;
	}
	
	
	/**
	 * Increment the flown count for a schedule
	 *
	 * @param string $code Airline code
	 * @param int $flightnum Flight number
	 * @return bool 
	 *
	 */
	public static function IncrementFlownCount($code, $flightnum)
	{
		$schedid = intval($schedid);
		
		$code = strtoupper($code);
		$flightnum = strtoupper($flightnum);
		
		$sql = 'UPDATE '.TABLE_PREFIX."schedules 
					SET timesflown=timesflown+1
					WHERE code='{$code}' AND flightnum='{$flightnum}'";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	
	/**
	 * Get detailed information about a schedule 
	 *
	 * @param int $id ID of the schedule
	 * @return array Schedule details
	 *
	 */
	public static function getScheduleDetailed($id)
	{
		$schedules = self::findSchedules(array('s.id' => $id));
		if(!$schedules)
			return false;
			
		return $schedules[0];
	}
	
	/**
	 * Return all the airports by depature, which have a schedule, for
	 *	a certain airline. If the airline
	 * @return object_array
	 */
	public static function getDepartureAirports($airlinecode='', $onlyenabled=false)
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
			$sql .= " AND s.code='{$airlinecode}' ";
			
		$sql .= ' ORDER BY depicao ASC';
									
		return DB::get_results($sql);
	}
	
	/**
	 * Get all of the airports which have a schedule, from
	 *	a certain airport, using the airline code. Code
	 *	is optional, otherwise it returns all of the airports.
	 * 
	 * @return database object
	 */
	public static function getArrivalAiports($depicao, $airlinecode='', $onlyenabled=true)
	{
		$depicao = strtoupper($depicao);
		$airlinecode = strtoupper($airlinecode);
		$depicao = DB::escape($depicao);
		
		if($onlyenabled)
			$enabled = 'AND s.enabled=1';
		else
			$enabled = '';
		
		$sql = 'SELECT DISTINCT s.arricao AS icao, a.name
				FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'airports a
				WHERE s.arricao = a.icao '.$enabled;

		if($airlinecode != '')
			$sql .= " AND s.code='{$airlinecode}' ";
		
		$sql .= ' ORDER BY depicao ASC';
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get all the schedules, $limit is the number to return
	 */
	public static function getSchedules($onlyenabled=true, $limit='', $start='')
	{
		$params = array();
		if($onlyenabled)
			$params['s.enabled'] = '1';
		
		return self::findSchedules($params, $limit, $start);
	}
	
	/**
	 * This gets all of the schedules which are disabled
	 */
	/*public static function getInactiveSchedules($count='', $start='')
	{
		$params = array('s.enabled'=>0);
		return self::findSchedules($params, $count, $start);
	}*/
	
	
	/**
	 * Calculate the distance between two coordinates
	 * Using a revised equation found on http://www.movable-type.co.uk/scripts/latlong.html
	 * 
	 * Also converts to proper type based on UNIT setting
	 *
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
	}
	
	/**
	 * Update a distance
	 * 
	 * @deprecated
	 */
	/*public static function UpdateDistance($scheduleid, $distance)
	{
		$sql = 'UPDATE '.TABLE_PREFIX."schedules 
				SET distance='{$distance}'
				WHERE id={$scheduleid}";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}*/
	
	/**
	 * Add a schedule
	 * 
	 * Pass in the following:
				$data = array(	'code'=>'',
						'flightnum'=''
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
	public static function addSchedule($data)
	{
	
		if(!is_array($data))
			return false;
		
		# Commented out to allow flights to/from the same airport
		#if($data['depicao'] == $data['arricao'])
		#	return false;
					
		$data['code'] = strtoupper($data['code']);
		$data['flightnum'] = strtoupper($data['flightnum']);			
		$data['deptime'] = strtoupper($data['deptime']);
		$data['arrtime'] = strtoupper($data['arrtime']);
		$data['depicao'] = strtoupper($data['depicao']);		
		$data['arricao'] = strtoupper($data['arricao']);
		
		if($data['enabled'] == true)
			$data['enabled'] = 1;
		else
			$data['enabled'] = 0;
				
		# If they didn't specify 
		$data['flighttype'] = strtoupper($data['flighttype']);
		if($data['flighttype'] == '')
			$data['flighttype'] = 'P';
			
		$data['flightlevel'] = str_replace(',', '', $data['flightlevel']);
		$data['maxload'] = str_replace(',', '', $data['maxload']);
			
		foreach($data as $key=>$value)
		{
			$data[$key] = DB::escape($value);
		}
		
		$data['flighttime'] = str_replace(':', '.', $data['flighttime']);
				
		$sql = "INSERT INTO " . TABLE_PREFIX ."schedules
					(`code`, `flightnum`, 
					 `depicao`, `arricao`, 
					 `route`, `aircraft`, `flightlevel`, `distance`, 
					 `deptime`, `arrtime`, 
					 `flighttime`, `daysofweek`, `maxload`, `price`, 
					 `flighttype`, `notes`, `enabled`)
				VALUES ('$data[code]', 
						'$data[flightnum]',
						'$data[depicao]', 
						'$data[arricao]', 
						'$data[route]', 
						'$data[aircraft]', 
						'$data[flightlevel]',
						'$data[distance]',
						'$data[deptime]', 
						'$data[arrtime]',
						'$data[flighttime]',
						'$data[daysofweek]',
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
							'flightnum'=''
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
	 
	public static function updateSchedule($data)
	{
		return self::editSchedule($data);
	}
	
	public static function editSchedule($data)
	{
		if(!is_array($data))
			return false;
		
		if($data['depicao'] == $data['arricao'])
			return false;
			
		$data['code'] = strtoupper($data['code']);
		$data['flightnum'] = strtoupper($data['flightnum']);			
		$data['deptime'] = strtoupper($data['deptime']);
		$data['arrtime'] = strtoupper($data['arrtime']);
		$data['depicao'] = strtoupper($data['depicao']);		
		$data['arricao'] = strtoupper($data['arricao']);
		
		if($data['enabled'] == true)
			$data['enabled'] = 1;
		else
			$data['enabled'] = 0;
					
		# If they didn't specify a flight type, just default to pax
		$data['flighttype'] = strtoupper($data['flighttype']);
		if($data['flighttype'] == '')
			$data['flighttype'] = 'P';
		
		$data['flightlevel'] = str_replace(',', '', $data['flightlevel']);
		$data['maxload'] = str_replace(',', '', $data['maxload']);
		
		foreach($data as $key=>$value)
		{
			$data[$key] = DB::escape($value);
		}
			
		$data['flighttime'] = str_replace(':', '.', $data['flighttime']);
		$sql = "UPDATE " . TABLE_PREFIX ."schedules 
				SET `code`='$data[code]', 
					`flightnum`='$data[flightnum]',
					`depicao`='$data[depicao]', 
					`arricao`='$data[arricao]',
					`route`='$data[route]', 
					`aircraft`='$data[aircraft]', 
					`flightlevel`='$data[flightlevel]',
					`distance`='$data[distance]', 
					`deptime`='$data[deptime]',
					`arrtime`='$data[arrtime]', 
					`flighttime`='$data[flighttime]', 
					`daysofweek`='$data[daysofweek]', 
					`maxload`='$data[maxload]',
					`price`='$data[price]',
					`flighttype`='$data[flighttype]',
					`notes`='$data[notes]', 
					`enabled`=$data[enabled]
				WHERE `id`=$data[id]";

		$res = DB::query($sql);
				
		if(DB::errno() != 0)
			return false;
			
		return true;
	}

	/**
	 * Update any fields in a schedule, other update functions come down to this
	 *
	 * @param int $scheduleid ID of the schedule to update
	 * @param array $fields Array, column name as key, with values to update
	 * @return bool 
	 *
	 */
	public static function updateScheduleFields($scheduleid, $fields)
	{
		return self::editScheduleFields($scheduleid, $fields);
	}
	
	/**
	 * Update any fields in a schedule, other update functions come down to this
	 *
	 * @param int $scheduleid ID of the schedule to update
	 * @param array $fields Array, column name as key, with values to update
	 * @return bool 
	 *
	 */
	public static function editScheduleFields($scheduleid, $fields)
	{
		if(!is_array($fields))
		{
			return false;
		}
		
		$sql = "UPDATE `".TABLE_PREFIX."schedules` SET ";
		$sql .= DB::build_update($fields);
		$sql .= ' WHERE `id`='.$scheduleid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
		{
			return false;
		}
		
		return true;
	}

	/**
	 * Delete a schedule
	 */
	public static function deleteSchedule($scheduleid)
	{
		$scheduleid = DB::escape($scheduleid);
		$sql = 'DELETE FROM ' .TABLE_PREFIX.'schedules 
				WHERE id='.$scheduleid;

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public static function getAllBids()
	{
		$sql = 'SELECT  p.*, s.*, 
						b.bidid as bidid, a.name as aircraft, a.registration
				FROM '.TABLE_PREFIX.'schedules s, 
					 '.TABLE_PREFIX.'bids b,
					 '.TABLE_PREFIX.'aircraft a,
					 '.TABLE_PREFIX.'pilots p
				WHERE b.routeid = s.id AND s.aircraft=a.id AND p.pilotid = b.pilotid
				ORDER BY b.bidid DESC';
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get the latest bids
	 */
	 
	public static function getLatestBids($limit=5)
	{
		$sql = 'SELECT  p.*, s.*, 
						b.bidid as bidid, a.name as aircraft, a.registration
				FROM '.TABLE_PREFIX.'schedules s, 
					 '.TABLE_PREFIX.'bids b,
					 '.TABLE_PREFIX.'aircraft a,
					 '.TABLE_PREFIX.'pilots p
				WHERE b.routeid = s.id AND s.aircraft=a.id AND p.pilotid = b.pilotid
				ORDER BY b.bidid DESC
				LIMIT '.$limit;
		
		return DB::get_results($sql);
	}
	
	public function getLatestBid($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		
		$sql = 'SELECT s.*, b.bidid, a.id as aircraftid, a.name as aircraft, a.registration, a.maxpax, a.maxcargo
				FROM '.TABLE_PREFIX.'schedules s, 
					 '.TABLE_PREFIX.'bids b,
					 '.TABLE_PREFIX.'aircraft a
				WHERE b.routeid = s.id 
					AND s.aircraft=a.id
					AND b.pilotid='.$pilotid.'
				ORDER BY id ASC LIMIT 1';
		
		return DB::get_row($sql);
	}
	
	/**
	 * Get a specific bid with route information
	 *
	 * @param unknown_type $bidid
	 * @return unknown
	 */
	public static function getBid($bidid)
	{
		$bidid = DB::escape($bidid);
		
		$sql = 'SELECT s.*, b.bidid, b.pilotid, b.routeid, 
						a.name as aircraft, a.registration
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
	public static function getBids($pilotid)
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
	public static function getBidWithRoute($pilotid, $code, $flightnum)
	{
		if($pilotid == '')
			return;
			
		$sql = 'SELECT b.bidid 
				FROM '.TABLE_PREFIX.'bids b, '.TABLE_PREFIX.'schedules s
				WHERE b.pilotid='.$pilotid.' 
					AND b.routeid=s.id
					AND s.code=\''.$code.'\' 
					AND s.flightnum=\''.$flightnum.'\'';
		
		return DB::get_row($sql);
	}
	
	public function setBidOnSchedule($scheduleid, $bidid)
	{
		$scheduleid = intval($scheduleid);
		$bidid = intval($bidid);
		
		$sql = 'UPDATE '.TABLE_PREFIX.'schedules
				SET `bidid`='.$bidid.'
				WHERE `id`='.$scheduleid;
					
		DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
	
	/**
	 * Add a bid
	 */		
	public static function addBid($pilotid, $routeid)
	{
		$pilotid = DB::escape($pilotid);
		$routeid = DB::escape($routeid);
		
		if(DB::get_row('SELECT bidid FROM '.TABLE_PREFIX.'bids
						WHERE pilotid='.$pilotid.' AND routeid='.$routeid))
		{
			return false;
		}
			
		$pilotid = DB::escape($pilotid);
		$routeid = DB::escape($routeid);
		
		$sql = 'INSERT INTO '.TABLE_PREFIX.'bids (pilotid, routeid, dateadded)
				VALUES ('.$pilotid.', '.$routeid.', CURDATE())';
		
		DB::query($sql);
		
		self::setBidOnSchedule($routeid, DB::$insert_id);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Remove a bid, by passing it's bid id
	 */
	public static function deleteBid($bidid)
	{
		self::removeBid($bidid);
	}
	
	/**
	 * Remove a bid, by passing it's bid id
	 */
	public static function removeBid($bidid)
	{
		$bidid = intval($bidid);
		$bid_info = self::getBid($bidid);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'bids 
				WHERE `bidid`='.$bidid;
		
		DB::query($sql);
		
		self::SetBidOnSchedule($bid_info->routeid, 0);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	
	/**
	 * @deprecated
	 *
	 */
	public static function getScheduleFlownCounts($code, $flightnum, $days=7)
	{
		$max = 0;
		
		
		$code = strtoupper($code);
		$flightnum = strtoupper($flightnum);
		
		$start = strtotime("- $days days");
		$end = time();
		$data = array();
				
		# Turn on caching:		
		DB::enableCache();
		
		do 
		{	
			$count = PIREPData::getReportCountForRoute($code, $flightnum, $start);
			$date = date('m-d', $start);
			
			$data[$date] = $count;			
			
			$start += SECONDS_PER_DAY;
			
		}  while ($start <= $end);
		
		DB::disableCache();
		
		return $data;
	}
	
	/**
	 * Show the graph of the past week's reports. Outputs the
	 *	image unless $ret == true
	 * 
	 * @deprecated
	 */
	public static function showReportCounts()
	{
		// Recent PIREP #'s
		$max = 0;
		$data = array();

		$time_start = strtotime('-7 days');
		$time_end = time();
		
		// This is for the past 7 days
		do {
			$count = PIREPData::getReportCount($time_start);
			
			$data[] = $count;
			
			if($count > $max)
				$max = $count;
				
			$time_start += SECONDS_PER_DAY;
		} while ($time_start < $time_end);
			
		return $data;
	}
	
	/* Below here, these are all deprecated. In your code, you should use
		the query structure, defined within the functions
		
	/**
	 * @deprecated
	 */
	/*public static function getSchedulesWithCode($code, $onlyenabled=true, $limit='', $start='')
	{
		$params = array('s.code' => strtoupper($code));
		if($onlyenabled)
			$params['s.enabled'] = '1';
		
		return self::findSchedules($params, $limit, $start);
	}*/
	
	/**
	 * @deprecated
	 */
	/*public static function getSchedulesWithFlightNum($flightnum, $onlyenabled=true, $limit='', $start='')
	{
		$params = array('s.flightnum' => $flightnum);
		if($onlyenabled)
			$params['s.enabled'] = '1';
		
		return self::findSchedules($params, $limit, $start);
	}*/
	
	/**
	 * Return all of the routes give the departure airport
	 * 
	 * @deprecated
	 */
	/*public static function getSchedulesWithDeparture($depicao, $onlyenabled = true, $limit = '', $start='')
	{
		self::getRoutesWithDeparture($depicao, $onlyenabled, $limit);
	}*/

	/**
	 * @deprecated
	 */
	/*public static function getRoutesWithDeparture($depicao, $onlyenabled=true, $limit='', $start='')
	{
		$params = array('s.depicao' => strtoupper($depicao));
		if($onlyenabled)
			$params['s.enabled'] = '1';
		
		return self::findSchedules($params, $limit, $start);
	}*/
	
	/**
	 * @deprecated
	 */
	/*public static function getRoutesWithArrival($arricao, $onlyenabled=true, $start='', $limit='')
	{
		return self::getSchedulesWithArrival($arricao, $onlyenabled, $limit);
	}*/
	
	/**
	 * @deprecated
	 */
	/*public static function getSchedulesWithArrival($arricao, $onlyenabled=true, $start='', $limit='')
	{
		$params = array('s.arricao' => strtoupper($arricao));
		if($onlyenabled)
			$params['s.enabled'] = '1';
		
		return self::findSchedules($params, $limit, $start);
	}*/
	
	/**
	 * @deprecated
	 */
	/*public static function getSchedulesByDistance($distance, $type, $onlyenabled=true, $start='', $limit='')
	{
		if($type == '')
			$type = '>';
			
		$params = array('s.distance' => trim($type).' '.$distance);
		if($onlyenabled)
			$params['s.enabled'] = '1';
		
		return self::findSchedules($params, $limit, $start);
	}*/
	
	/**
	 * Search schedules by the equipment type
	 * 
	 * @deprecated
	 */
	/*public static function getSchedulesByEquip($ac, $onlyenabled = true, $start='', $limit='')
	{
		$params = array('a.name' => $ac);
		if($onlyenabled)
			$params['s.enabled'] = '1';
		
		return self::findSchedules($params, $limit, $start);
	}*/
}