<?php
/**
 * OperationsData
 *
 * Database model for any data related to operations:
 * Airports, Fleet, and Scheduling
 * 
 * @author Nabeel Shahzad <contact@phpvms.net>
 * @copyright Copyright (c) 2008, phpVMS Project
 * @license http://www.phpvms.net/license.php
 * 
 * @package OperationsData
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
	
	function GetAllAircraft()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'aircraft ORDER BY icao ASC');
	}
	
	function GetAllAirports()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'airports ORDER BY name ASC');
	}
	
	function GetAircraftInfo($id)
	{
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'aircraft WHERE id='.$id);	
	}
	
	function AddAirline($code, $name)
	{
	
		$code = strtoupper($code);
		
		$sql = "INSERT INTO " .TABLE_PREFIX."airlines (code, name) VALUES ('$code', '$name')";
		
		return DB::query($sql);	
	}
	function AddAircaft($icao, $name, $fullname, $range, $weight, $cruise)
	{
		$icao = strtoupper($icao);
		
		$sql = "INSERT INTO " . TABLE_PREFIX . "aircraft (icao, name, fullname, range, weight, cruise)
					VALUES ('$icao', '$name', '$fullname', '$range', '$weight', '$cruise')";
		
		return DB::query($sql);
		
	}
	
	function EditAircraft($id, $icao, $name, $fullname, $range, $weight, $cruise)
	{
		$icao = strtoupper($icao);
		
		$sql = "UPDATE " . TABLE_PREFIX."aircraft SET icao='$icao', name='$name', fullname='$fullname',
					range='$range', weight='$weight', cruise='$cruise' WHERE id=$id";
		
		return DB::query($sql);
	}
	
	function AddAirport($icao, $name, $country, $lat, $long)
	{
	
		$icao = strtoupper($icao);
		
		$sql = "INSERT INTO " . TABLE_PREFIX ."airports (icao, name, country, lat, lng)
					VALUES ('$icao', '$name', '$country', $lat, $long)";
		
		return DB::query($sql);
	}
	
	function GetAirportInfo($icao)
	{
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'airports WHERE icao=\''.$icao.'\'');
	}	
}
?>