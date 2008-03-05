<?php


class OperationsData
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
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'airports WHERE icao=\''.$icao.'\'');
	}	
}
?>