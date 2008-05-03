<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 *  This program is free software; you can redistribute it and/or modify it 
 *  under the terms of the GNU General Public License as published by the Free 
 *	Software Foundation; either version 2 of the License, or (at your option) 
 *	any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT 
 *  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 *	FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with 
 *	this program; if not, write to the:
 *		Free Software Foundation, Inc., 
 *		59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license GNU Public License http://opensource.org/licenses/gpl-license.php
 * @package core_api
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
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX .'airports ORDER BY icao ASC');
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
		$name = strtoupper($name);
		
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
	
	function AddAirport($icao, $name, $country, $lat, $long, $hub)
	{
	
		$icao = strtoupper($icao);

		if($hub == true)
			$hub = 1;
		else
			$hub = 0;

		$sql = "INSERT INTO " . TABLE_PREFIX ."airports (icao, name, country, lat, lng, hub)
					VALUES ('$icao', '$name', '$country', $lat, $long, $hub)";

		return DB::query($sql);
	}

	function EditAirport($icao, $name, $country, $lat, $long, $hub)
	{
        if($hub == true)
			$hub = 1;
		else
			$hub = 0;

		$sql = "UPDATE " . TABLE_PREFIX ."airports
					SET name='$name', country='$country', lat=$lat, lng=$long, hub=$hub
					WHERE icao='$icao'";

		return DB::query($sql);
	}
	
	function GetAirportInfo($icao)
	{
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'airports WHERE icao=\''.$icao.'\'');
	}	
}
?>