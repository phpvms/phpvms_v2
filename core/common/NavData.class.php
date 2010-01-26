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

class NavData extends CodonData
{

	/**
	 * Pass in a string with the route, and return back an array
	 * with the data about each segment of the route. Pass a schedule
	 * result into it.
	 * 
	 * You can pass in a PIREP, schedule, or ACARS result, as long as it
	 * has the following fields:
	 *	lat
	 *	lng
	 *	route
	 * 
	 * To cache the route, use ScheduleData::getRouteDetails() instead.
	 * This function bypasses any cached info
	 *
	 * @param mixed $route_string This is a description
	 * @return mixed This is the return value description
	 *
	 */
	public static function parseRoute($schedule)
	{
		$fromlat = $schedule->deplat;
		$fromlng = $schedule->deplng;
		$route_string = $schedule->route;
				
		if(empty($route_string))
		{
			return array();
		}

		$navpoints = explode(' ', $route_string);
		# Expand out the route, parsing airway segments
		$navpoints = self::getAllPoints($navpoints);
		
		# Now get all the details about the point
		$point_array = self::getNavDetails($navpoints);
		$total = count($navpoints);
		
		$nat_pattern = '/^([0-9]+)([A-Za-z]+)/';
				
		/*	How will this work - loop through each point, and
			decide which one we'll use, determined by the
			one which is the shortest distance from the previous 
			
			Go in the order of the ones passed in.
		*/
		for($i = 0; $i < $total; $i++)
		{
			$point_name = $navpoints[$i];
			$results_count = count($point_array[$point_name]);
			
			if($results_count == 0)
			{
				/*	Check here if what's listed is part of a NAT or POT
					They're listed in pairs
				
					5900N
					02000W
					
					6000N
					03000W
				 */
				preg_match($nat_pattern, $point_name, $matches);
				
				/*	Means it is a track, so go into processing it */
				if(count($matches) > 0)
				{
					$name = $point_name;
					
					$coord = $matches[1];
					$lat = $matches[2].$coord[0].$coord[1].'.'.$coord[2].$coord[3];
					
					/*	Match the second set of coordinates */
					$i++;
					$point_name = $navpoints[$i];
					
					# Read the second set
					preg_match($nat_pattern, $point_name, $matches);
					if($matches == 0)
					{
						continue;
					}
					
					$coord = $matches[1];
					$lng = $matches[2].$coord[0].$coord[1].$coord[2].'.'.$coord[3];
					
					/*	Now convert into decimal coordinates */
					$coords = $lat.' '.$lng;
					$coords = Util::get_coordinates($coords);
										
					$name .= "{$point_name}";
					
					$tmp =  new stdClass();
					$tmp->id = 0;
					$tmp->type = NAV_TRACK;
					$tmp->name = $name;
					$tmp->title = $name;
					$tmp->lat = $coords['lat'];
					$tmp->lng = $coords['lng'];
					
					$return[] = $tmp;
					
					unset($point_name);
					unset($matches);
					unset($tmp);
					
					continue;
				}
				
				
				//48N015W
				preg_match('/^(\d*)([A-Za-z]).(\d*)([A-Za-z])/', $line, $matches);
				
				/*	Means it is a track, so go into processing it */
				if(count($matches) > 0)
				{
					# Convert to format
				}

			}
			elseif($results_count == 1)
			{
				$return[] = $point_array[$point_name][0];
			}
			elseif($results_count > 1)
			{
				/* There is more than one, so find the one with the shortest
					distance from the previous point out of all the ones */
				
				$index = 0; $dist = 0;
				
				/* Set the inital settings */
				$lowest = $point_array[$point_name][0];
				$lowest_dist = SchedulesData::distanceBetweenPoints($fromlat, $fromlng, $lowest->lat, $lowest->lng);

				foreach($point_array[$point_name] as $p)
				{
					$dist = SchedulesData::distanceBetweenPoints($fromlat, $fromlng, $p->lat, $p->lng);
					
					if($dist < $lowest_dist)
					{
						$lowest = $p;
						$lowest_dist = $dist;
					}
					
					$index++;
				}
				
				$fromlat = $lowest->lat;
				$fromlng = $lowest->lng;
				$return[] = $lowest;
			}
		}
	
		return $return;
	}
	
	protected static function cleanName($name)
	{
		if(substr_count($name, '/') > 0)
		{
			$tmp = explode('/', $name);
			$name = $tmp[0];
			unset($tmp);
		}
		
		return $name;
	}
	
	/**
	 * This returns all the navpoints - checks if a point
	 * is an airway, or a "normal" point. If it's an airway,
	 * it will expand and place all the points in between the
	 * entry and exit points of that listed airway
	 *
	 * @param array $navpoints array of nav points
	 * @return array Complete list of waypoints
	 *
	 */
	protected static function getAllPoints($navpoints)
	{
		$allpoints = array();
		$total = count($navpoints);
		
		for($i = 0; $i < $total; $i++)
		{
			$name = self::cleanName($navpoints[$i]);
			$airway = self::getAirway($name);
			
			if(is_object($airway))
			{
				$entry_name = self::cleanName($navpoints[$i-1]);
				$exit_name = self::cleanName($navpoints[$i+1]);
				$airway_points = explode(' ', $airway->points);
				
				# Find the locations of the entry and exit points
				$entry = array_search($entry_name, $airway_points) + 1;
				$exit = array_search($exit_name, $airway_points);
			
				# Get the lesser - 
				if($entry < $exit)
				{
					$start = $entry;
				}
				elseif($entry > $exit)
				{
					$start = $exit;
				}
				else
				{
					continue;
				}					
				
				# Get all of the points in between (if there are any)
				$points = array_slice($airway_points, $start, abs($exit - ($entry)));
				
				if(empty($points))
				{
					continue;
				}
				
				// remove the start and ends
				$idx = array_search($entry_name, $points);
				unset($points[$idx]);
				$idx = array_search($exit_name, $points);
				unset($points[$idx]);
				
				$allpoints = array_merge($allpoints, $points);
			}
			else
			{
				# This isn't an airway, it's a normal point
				$allpoints[] = $name;
			}
		}
		
		return $allpoints;
	}
	
	protected static function getAirway($name)
	{
		$sql='SELECT `name`, `points` '
			.'FROM '.TABLE_PREFIX.'airways '
			."WHERE `name`='{$name}'";
			
		return DB::get_row($sql);
	}
	
	protected static function getNavDetails($navpoints)
	{
		/*	Form an IN clause so we can easily grab all the points
			which we have cached locally in the navdb table
			
			Check if an array was passed, or a string of points */
		if(is_array($navpoints) && count($navpoints) > 0)
		{
			$in_clause = array();
			foreach($navpoints as $point)
			{
				$in_clause[] = "'{$point}'";
			}
			
			$in_clause = implode(', ', $in_clause);
		}
		else
		{
			# Add commas in between, since it's space separated
			$navpoints = str_replace(' ', ', ', $navpoints);
			$in_clause = "'{$navpoints}'";
		}
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'navdb
				WHERE name IN ('.$in_clause.')';
		
		$results =  DB::get_results($sql);
		
		/* Means nothing was returned locally */
		if(!$results)
		{
			return self::navDetailsFromServer($navpoints);
		}
		else
		{
			/*	Form an array of what to return from the server,
				see what we did and didn't return */
			$notfound = array();
			$point_array = array();
			foreach($results as $row)
			{
				/*	Find all instances of the navpoint in what was
					returned, and then remove it. In the end, only the
					ones which haven't been returned  are left in the 
					array */
				$keys = array_keys($navpoints, $row->name);
				foreach($keys as $k) 
				{
					unset($navpoints[$k]);
				}
				
				if($row->lat == 0 || $row->lng == 0)
				{
					continue;
				}
				
				$point_array[$row->name][] = $row;
			}
			
			/* These are the navpoints left over which we didn't
				find, so try to get their information from above */
			if(count($navpoints) > 0)
			{
				$temp = self::navDetailsFromServer($navpoints);
				$point_array = array_merge($point_array, $temp);
				unset($temp);
			}
		}
		
		return $point_array;
	}
	
	protected static function navDetailsFromServer($navpoints)
	{
		if(!is_array($navpoints) && count($navpoints) == 0)
		{
			return array();
		}
		
		/*	Send a simple XML string over:
		
			<phpvms>
				<navpoints>
					<navpoint>NAME</navpoint>
					<navpoint>NAME2</navpoint>
				</navpoints>
			</phpvms>
			
			
			@TODO: Convert send format to json, much smaller
		*/
		$xml = new SimpleXMLElement('<phpvms/>');
		$nav_xml = $xml->addChild('navpoints');
		
		foreach($navpoints as $p)
		{
			$nav_xml->addChild('navpoint', $p);
		}
		
		/*	Send the request, data is returned as JSON format */
		$web_service = new CodonWebService();
		$xml_response = $web_service->post(Config::Get('PHPVMS_API_SERVER').'/navdata/get/json', $xml->asXML());
		
		$insert = array();
		$sql = 'INSERT INTO '.TABLE_PREFIX."navdb
				(`type`, `name`, `title`, `freq`, `lat`, `lng`) VALUES ";
		
		if(empty($xml_response))
		{
			/*	None of those exist on the server, but cache them on this
				side so we don't keep checking over and over */
			foreach($navpoints as $point)
			{
				$insert[] = "(0, '{$point}', '{$point}', '0', '0', '0')";
			}	
			
			$sql .= implode(',', $insert);
			DB::query($sql);
			
			return array();
		}
		
		$returned_points = json_decode($xml_response);
		
		if(empty($returned_points))
		{	
			foreach($navpoints as $point)
			{
				$insert[] = "(0, '{$point}', '{$point}', '0', '0', '0')";
			}	
			
			$sql .= implode(',', $insert);
			DB::query($sql);
			
			return array();
		}
		
		$return = array();
		foreach($returned_points as $point)
		{
			$keys = array_keys($navpoints, $point->name);
			foreach($keys as $k) 
			{
				unset($navpoints[$k]);
			}
			
			$return[$point->name][] = $point;
			$insert[] = "({$point->type}, '{$point->name}', '{$point->title}', '{$point->freq}', '{$point->lat}', '{$point->lng}')";
		}
		
		// Then the ones not listed
		foreach($navpoints as $point)
		{
			$insert[] = "(0, '{$point}', '{$point}', '0', '0', '0')";
		}
		
		$sql .= implode(',', $insert);
		DB::query($sql);
		
		return $return;
	}
}