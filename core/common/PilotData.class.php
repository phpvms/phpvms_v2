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
 * @package phpvms
 * @subpackage pilot_data
 */
 
class PilotData
{
	/** 
	 * Get all the pilots, or the pilots who's last names start
	 * with the letter
	 */
	function GetAllPilots($letter='')
	{
		$sql = 'SELECT * FROM ' . TABLE_PREFIX .'pilots ';
		
		if($letter!='')
			$sql .= " WHERE lastname LIKE '$letter%' ";

		$sql .= ' ORDER BY lastname DESC';
		
		return DB::get_results($sql);
	}
	
	/**
	 * Get all the detailed pilot's information
	 */
	function GetAllPilotsDetailed($start='', $limit=20)
	{
	
		$sql = 'SELECT p.*, r.rankimage FROM '.TABLE_PREFIX.'pilots p, '.TABLE_PREFIX.'ranks r
					WHERE r.rank = p.rank
					ORDER BY totalhours DESC';
		
		if($start!='')
			$sql .= ' LIMIT '.$start.','.$limit;
			
		return DB::get_results($sql);	
	}
	
	/**
	 * Return the pilot's code (ie DVA1031), using
	 * the code and their DB ID
	 */
	function GetPilotCode($code, $pilotid)
	{
		$pilotid = $pilotid + PILOTID_OFFSET;
		return $code . str_pad($pilotid, 4, '0', STR_PAD_LEFT);
	}
	
	/**
	 * The the basic pilot information 
	 */
	function GetPilotData($pilotid)
	{
		$sql = 'SELECT *, UNIX_TIMESTAMP(lastlogin) as lastlogin
					FROM '.TABLE_PREFIX.'pilots WHERE pilotid='.$pilotid;
		
		return DB::get_row($sql);
	}
	
	/**
	 * Get a pilot's information by email
	 */
	function GetPilotByEmail($email)
	{
		$sql = 'SELECT * FROM '. TABLE_PREFIX.'pilots WHERE email=\''.$email.'\'';
		return DB::get_row($sql);
	}

	/**
	 * Get the list of all the pending pilots
	 */
	function GetPendingPilots($count='')
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots WHERE confirmed='.PILOT_PENDING;

		if($count!='')
			$sql .= ' LIMIT '.intval($count);

			
		return DB::get_results($sql);
	}
	
	/**
	 * Save the email and location changes to the pilot's prfile
	 */
	function SaveProfile($pilotid, $email, $location, $hub='')
	{
		$sql = "UPDATE ".TABLE_PREFIX."pilots SET email='$email', location='$location' ";
		
		if($hub!= '')
			$sql.=", hub='$hub' ";
			
		$sql .= 'WHERE pilotid='.$pilotid;
		
		$ret = DB::query($sql);
		
		return $ret;
	}
	
	/**
	 * Accept the pilot (allow them into the system)
	 */
	function AcceptPilot($pilotid)
	{
		$sql = 'UPDATE ' . TABLE_PREFIX.'pilots SET confirmed='.PILOT_ACCEPTED.'
					WHERE pilotid='.$pilotid;
		DB::query($sql);
	}
	
	/**
	 * Reject a pilot
	 */
    function RejectPilot($pilotid)
	{
		$sql = 'UPDATE ' . TABLE_PREFIX.'pilots SET confirmed='.PILOT_REJECTED.'
					WHERE pilotid='.$pilotid;

		DB::query($sql);
	}
	
	/**
	 * Update the login time
	 */
	function UpdateLogin($pilotid)
	{
		$sql = "UPDATE ".TABLE_PREFIX."pilots SET lastlogin=NOW() WHERE pilotid=$pilotid";
		
		$ret = DB::query($sql);
		
		return $ret;
	}
	
	/**
	 * After a PIREP been accepted, update their statistics
	 */
	function UpdateFlightData($pilotid, $flighttime, $numflights=1)
	{
		$sql = "UPDATE " .TABLE_PREFIX."pilots 
					SET totalhours=totalhours+$flighttime, totalflights=totalflights+$numflights
					WHERE pilotid=$pilotid";
		
		return DB::query($sql);		
	}
	
	/**
	 * Don't update the pilot's flight data, but just replace it
	 * 	with the values given
	 */
	function ReplaceFlightData($pilotid, $flighttime, $numflights)
	{
		$sql = "UPDATE " .TABLE_PREFIX."pilots 
					SET totalhours=$flighttime, totalflights=$numflights
					WHERE pilotid=$pilotid";
		
		return DB::query($sql);	
	}
	
	/**
	 * Save the custom fields. Usually just pass the $_POST
	 */
	function SaveFields($pilotid, $list)
	{
		$allfields = RegistrationData::GetCustomFields();
		
		
		if(!$allfields)
			return true;
			
		foreach($allfields as $field)
		{			
			$sql = 'SELECT id FROM '.TABLE_PREFIX.'fieldvalues WHERE fieldid='.$field->fieldid.' AND pilotid='.$pilotid;
			$res = DB::get_row($sql);

			$fieldname =str_replace(' ', '_', $field->fieldname);
			$value = $list[$fieldname];
				
			// if it exists
			if($res)
			{
				$sql = 'UPDATE '.TABLE_PREFIX.'fieldvalues 
						SET value="'.$value.'" WHERE fieldid='.$field->fieldid.' AND pilotid='.$pilotid;
			}
			else
			{
				$sql = "INSERT INTO ".TABLE_PREFIX."fieldvalues 
						(fieldid, pilotid, value) VALUES ($field->fieldid, $pilotid, '$value')";
			}
			
			DB::query($sql);
		}
		
		return true;
	}
	
	/**
	 * Get all of the "cusom fields" for a pilot
	 */
	function GetFieldData($pilotid, $inclprivate=false)
	{
		$sql = 'SELECT f.title, f.fieldname, v.value 
					FROM '.TABLE_PREFIX.'customfields f 
					LEFT JOIN '.TABLE_PREFIX.'fieldvalues v
						ON f.fieldid=v.fieldid AND v.pilotid='.$pilotid;
								
		if($inclprivate == false)
			$sql .= " AND f.public=1";
			
		return DB::get_results($sql);
	}
	
	/**
	 * Get the groups a pilot is in
	 */
	function GetPilotGroups($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		
		$sql = 'SELECT g.groupid, g.name
					FROM ' . TABLE_PREFIX . 'groupmembers u, ' . TABLE_PREFIX . 'groups g
					WHERE u.pilotid='.$pilotid.' AND g.groupid=u.groupid';
		
		$ret = DB::get_results($sql);
		
		return $ret;		
	}
}

?>