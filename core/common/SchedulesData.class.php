<?php


class SchedulesData
{
	
	function GetAllAirports()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'airports ORDER BY name ASC');
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
		// Look for %icao in case the 3 letter is supplied (JFK instead of KJFK)
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'airports WHERE icao LIKE \'%'.$icao.'\'');
	}	
}
?>