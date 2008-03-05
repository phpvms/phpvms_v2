<?php


class SchedulesData
{
	
	function GetAllAirports()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'airports ORDER BY name ASC');
	}
	
	function AddAirport($icao, $name, $country, $lat, $long)
	{
	
		$sql = "INSERT INTO " . TABLE_PREFIX ."airports (icao, name, country, lat, long)
					VALUES ('$icao', '$name', '$country', '$lat', '$long')";
		
		return DB::query($sql);
	}
	
}
?>