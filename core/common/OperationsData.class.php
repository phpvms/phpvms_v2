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
	
	public static function GetAllAirlines($onlyenabled=false)
	{
		if($onlyenabled == true) $where = 'WHERE `enabled`=1';
		else $where = '';
		
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'airlines 
									'.$where.' 
									ORDER BY `code` ASC');
	}
	
	/**
	 * Get all of the hubs
	 */
	public static function GetAllHubs()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'airports 
					WHERE `hub`=1
					ORDER BY `icao` ASC';
		return DB::get_results($sql);
	}
	
	/**
	 * Get all of the aircraft
	 */
	public static function GetAllAircraft($onlyenabled=false)
	{
		$sql = 'SELECT * 
					FROM ' . TABLE_PREFIX .'aircraft';
					
		if($onlyenabled == true)
		{
			$sql .= ' WHERE `enabled`=1 ';
		}
		
		$sql .= ' ORDER BY icao ASC';
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get all of the aircraft
	 */
	public static function GetAllAircraftSearchList($onlyenabled=false)
	{
		$sql = 'SELECT * 
				FROM ' . TABLE_PREFIX .'aircraft';
		
		if($onlyenabled == true)
		{
			$sql .= ' WHERE `enabled`=1 ';
		}
		
		$sql .= 'GROUP BY `name`
				 ORDER BY `icao` ASC';
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get an aircraft according to registration
	 */
	public static function GetAircraftByReg($registration)
	{
		$registration = DB::escape(strtoupper($registration));
		
		$sql = 'SELECT * 
					FROM ' . TABLE_PREFIX .'aircraft 
					WHERE `registration`=\''.$registration.'\'';
								
		return DB::get_row($sql);
	}
	
	/** 
	 * Check an aircraft registration, against an ID and a 
	 *  registration. For instance, editing an aircraft with a 
	 *  registration change. This checks to see if that reg is
	 *  being already used
	 */
	 
	 public static function CheckRegDupe($acid, $reg)
	 {
		# Search for reg that's not on the AC supplied
		$sql = "SELECT * FROM ".TABLE_PREFIX."aircraft
					WHERE `id` != $acid
						AND `registration`='$reg'";
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get all of the airports
	 */
	public static function GetAllAirports()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'airports 
									ORDER BY `icao` ASC');
	}
	
	/**
	 * Get information about a specific aircraft
	 */
	public static function GetAircraftInfo($id)
	{
		$id = DB::escape($id);
		
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'aircraft 
								WHERE `id`='.$id);
	}
	
	public static function GetAirlineByCode($code)
	{
		$code = strtoupper($code);
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'airlines 
								WHERE `code`=\''.$code.'\'');
	}
	
	public static function GetAirlineByID($id)
	{
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'airlines 
								WHERE `id`=\''.$id.'\'');
	}	
	
	/**
	 * Add an airline
	 */
	public static function AddAirline($code, $name)
	{
		
		$code = strtoupper($code);
		$name = DB::escape($name);
		
		$sql = "INSERT INTO " .TABLE_PREFIX."airlines (
						`code`, `name`) 
					VALUES ('$code', '$name')";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public static function EditAirline($id, $code, $name, $enabled=true)
	{
		$code = DB::escape($code);
		$name = DB::escape($name);
		
		if($enabled) $enabled = 1;
		else $enabled = 0;
		
		$sql = "UPDATE ".TABLE_PREFIX."airlines 
					SET `code`='$code', `name`='$name', `enabled`=$enabled 
					WHERE id=$id";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Add an aircraft
	 */
	public static function AddAircaft($icao, $name, $fullname, $registration, $downloadlink,
										$imagelink, $range, $weight, $cruise, 
										$maxpax, $maxcargo,
										$enabled=true)
	{
		$icao = DB::escape(strtoupper($icao));
		$name = DB::escape(strtoupper($name));
		$registration = DB::escape(strtoupper($registration));
		
		$range = ($range == '') ? 0 : $range;
		$weight = ($weight == '') ? 0 : $weight;
		$cruise = ($cruise == '') ? 0 : $cruise;
		
		if($enabled == true)
			$enabled = 1;
		else
			$enabled = 0;
		
		$sql = "INSERT INTO ".TABLE_PREFIX."aircraft (
					`icao`, `name`, `fullname`, `registration`, `downloadlink`,
					`imagelink`, `range`, `weight`, `cruise`, 
					`maxpax`, `maxcargo`, `enabled`)
				VALUES (
					'$icao', '$name', '$fullname', '$registration', '$downloadlink', 
					'$imagelink', '$range', '$weight', '$cruise', 
					'$maxpax', '$maxcargo', $enabled)";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Edit an aircraft
	 */
	public static function EditAircraft($id, $icao, $name, $fullname, 
										$registration, $downloadlink, $imagelink,
										$range, $weight, $cruise, 
										$maxpax, $maxcargo, $enabled=true)
	{
		$icao = DB::escape(strtoupper($icao));
		$name = DB::escape(strtoupper($name));
		$registration = DB::escape(strtoupper($registration));
		
		if($enabled == true)
			$enabled = 1;
		else
			$enabled = 0;

		$sql = "UPDATE " . TABLE_PREFIX."aircraft 
					SET `icao`='$icao', `name`='$name', `fullname`='$fullname',
						`registration`='$registration', `downloadlink`='$downloadlink', 
						`imagelink`='$imagelink', `range`='$range', `weight`='$weight',
						`cruise`='$cruise', `maxpax`='$maxpax', `maxcargo`='$maxcargo',
						`enabled`=$enabled
					WHERE `id`=$id";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Add an airport
	 */
	public static function AddAirport($icao, $name, $country, $lat, $long, $hub, $fuelprice=0)
	{
	
		if($icao == '')
			return false;
			
		$icao = strtoupper($icao);
			

		if($hub === true)
			$hub = 1;
		else
			$hub = 0;
			
		if($fuelprice == '')
			$fuelprice = 0;

		$sql = "INSERT INTO " . TABLE_PREFIX ."airports 
					(	`icao`, `name`, `country`, `lat`, `lng`, `hub`, `fuelprice`)
					VALUES (
						'$icao', '$name', '$country', $lat, $long, $hub, $fuelprice)";

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}

	/**
	 * Edit the airport
	 */
	public static function EditAirport($icao, $name, $country, $lat, $long, $hub, $fuelprice=0)
	{
        if($hub == true)
			$hub = 1;
		else
			$hub = 0;
		
		$icao = strtoupper(DB::escape($icao));
		$name = DB::escape($name);

		$sql = "UPDATE " . TABLE_PREFIX . "airports
					SET `icao`='$icao', `name`='$name', `country`='$country', 
						`lat`=$lat, `lng`=$long, `hub`=$hub, `fuelprice`=$fuelprice
					WHERE `icao`='$icao'";

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
		
	/**
	 * Get information about an airport
	 */
	public static function GetAirportInfo($icao)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'airports 
					WHERE `icao`=\''.$icao.'\'';
		
		return DB::get_row($sql);
	}
	
	
	/**
	 * Get the distance between two airports
	 *
	 * @param string $depicao ICAO of the departure airport
	 * @param string $arricao ICAO of the destination airport
	 * @return int The distance
	 *
	 */
	public static function getAirportDistance($depicao, $arricao)
	{
		
		$dep = self::GetAirportInfo($depicao);
		$arr = self::GetAirportInfo($arricao);
		
		return SchedulesData::distanceBetweenPoints($dep->lat, $dep->lng, $arr->lat, $arr->lng);	
	}
	
	/**
	 * Retrieve Airport Information
	 */
	 
	public static function RetrieveAirportInfo($icao)
	{
		$url = GEONAME_URL.'/search?maxRows=1&featureCode=AIRP&q=';
		
		$reader = simplexml_load_file($url.$icao);
		if($reader->totalResultsCount == 0 || !$reader)
		{
			return false;
		}
		else
		{
			// Add the AP
			OperationsData::AddAirport($icao, $reader->geoname->name, $reader->geoname->countryName,
					$reader->geoname->lat, $reader->geoname->lng, false);
		}
		
		return self::GetAirportInfo($icao);
	}
}