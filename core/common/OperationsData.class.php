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
 
class OperationsData
{
	/**
	 * Get all aircraft from database
	 */
	
	function GetAllAirlines()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'airlines ORDER BY code ASC');
	}
	
	/**
	 * Get all of the hubs
	 */
	function GetAllHubs()
	{
		return DB::get_results('SELECT * FROM '.TABLE_PREFIX.'airports WHERE hub=1
								ORDER BY icao ASC');
	}
	
	/**
	 * Get all of the aircraft
	 */
	function GetAllAircraft()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'aircraft ORDER BY icao ASC');
	}
	
	/**
	 * Get all of the airports
	 */
	function GetAllAirports()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'airports ORDER BY icao ASC');
	}
	
	/**
	 * Get information about a specific aircraft
	 */
	function GetAircraftInfo($id)
	{
		$id = DB::escape($id);
		
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'aircraft WHERE id='.$id);
	}
	
	function GetAirline($code)
	{
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'airlines WHERE code=\''.$code.'\'');
	}
	
	/**
	 * Add an airline
	 */
	function AddAirline($code, $name)
	{
		
		$code = strtoupper($code);
		$name = DB::escape($name);
		
		$sql = "INSERT INTO " .TABLE_PREFIX."airlines (code, name) VALUES ('$code', '$name')";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Add an aircraft
	 */
	function AddAircaft($icao, $name, $fullname, $range, $weight, $cruise)
	{
		$icao = strtoupper($icao);
		$name = strtoupper($name);
		
		$sql = "INSERT INTO " . TABLE_PREFIX . "aircraft (icao, name, fullname, range, weight, cruise)
					VALUES ('$icao', '$name', '$fullname', '$range', '$weight', '$cruise')";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Edit an aircraft
	 */
	function EditAircraft($id, $icao, $name, $fullname, $range, $weight, $cruise)
	{
		$icao = strtoupper($icao);

		$sql = "UPDATE " . TABLE_PREFIX."aircraft SET icao='$icao', name='$name', fullname='$fullname',
					range='$range', weight='$weight', cruise='$cruise' WHERE id=$id";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Add an airport
	 */
	function AddAirport($icao, $name, $country, $lat, $long, $hub)
	{
	
		$icao = strtoupper($icao);

		if($hub == true)
			$hub = 1;
		else
			$hub = 0;

		$sql = "INSERT INTO " . TABLE_PREFIX ."airports (icao, name, country, lat, lng, hub)
					VALUES ('$icao', '$name', '$country', $lat, $long, $hub)";

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}

	/**
	 * Edit the airport
	 */
	function EditAirport($icao, $name, $country, $lat, $long, $hub)
	{
        if($hub == true)
			$hub = 1;
		else
			$hub = 0;

		$sql = "UPDATE " . TABLE_PREFIX . "airports
					SET name='$name', country='$country', lat=$lat, lng=$long, hub=$hub
					WHERE icao='$icao'";

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Get information about an airport
	 */
	function GetAirportInfo($icao)
	{
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'airports WHERE icao=\''.$icao.'\'');
	}
}
?>