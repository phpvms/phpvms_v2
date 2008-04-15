<?php
/**
 * PilotData
 *
 * Model for pilot-related data (lists, specific information)
 * 
 * @author Nabeel Shahzad <contact@phpvms.net>
 * @copyright Copyright (c) 2008, phpVMS Project
 * @license http://www.phpvms.net/license.php
 * 
 * @package PilotData
 */

class PilotData
{
	
	function GetAllPilots($letter='')
	{
		$sql = 'SELECT * FROM ' . TABLE_PREFIX .'pilots ';
		
		if($letter!='')
			$sql .= " WHERE lastname LIKE '$letter%' ";

		$sql .= ' ORDER BY lastname DESC';
		
		return DB::get_results($sql);
	}
	
	function GetPilotCode($code, $pilotid)
	{
		return $code . str_pad($pilotid, 4, '0', STR_PAD_LEFT);
	}
	
	function GetPilotData($pilotid)
	{
		$sql = 'SELECT *, UNIX_TIMESTAMP(lastlogin) as lastlogin
					FROM '.TABLE_PREFIX.'pilots WHERE pilotid='.$pilotid;
		
		return DB::get_row($sql);
	}
	
	function GetPilotByEmail($email)
	{
		$sql = 'SELECT * FROM '. TABLE_PREFIX.'pilots WHERE email=\''.$email.'\'';
		return DB::get_row($sql);
	}

	function GetPendingPilots($count='')
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots WHERE confirmed='.PILOT_PENDING;

		if($count!='')
			$sql .= ' LIMIT '.intval($count);

			
		return DB::get_results($sql);
	}
	function SaveProfile($pilotid, $email, $location)
	{
		$sql = "UPDATE ".TABLE_PREFIX."pilots SET email='$email', location='$location' WHERE pilotid=$pilotid";
		
		$ret = DB::query($sql);
		
		return $ret;
	}

	function AcceptPilot($pilotid)
	{
		$sql = 'UPDATE ' . TABLE_PREFIX.'pilots SET confirmed=1
					WHERE pilotid='.$pilotid;
		DB::query($sql);
	}

    function RejectPilot($pilotid)
	{
		$sql = 'UPDATE ' . TABLE_PREFIX.'pilots SET confirmed=2
					WHERE pilotid='.$pilotid;

		DB::query($sql);
	}
	
	function UpdateLogin($pilotid)
	{
		$sql = "UPDATE ".TABLE_PREFIX."pilots SET lastlogin=NOW() WHERE pilotid=$pilotid";
		
		$ret = DB::query($sql);
		
		return $ret;
	}
	
	function UpdateFlightData($pilotid, $flighttime, $numflights=1)
	{
		$sql = "UPDATE " .TABLE_PREFIX."pilots 
					SET totalhours=totalhours+$flighttime, totalflights=totalflights+$numflights
					WHERE pilotid=$pilotid";
		
		return DB::query($sql);		
	}
	
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
	
	function GetFieldData($pilotid, $inclprivate=false)
	{
		$sql = 'SELECT f.title, f.fieldname, v.value 
					FROM '.TABLE_PREFIX.'customfields f 
					LEFT JOIN '.TABLE_PREFIX.'fieldvalues v
						ON f.fieldid=v.fieldid AND v.pilotid='.$pilotid;
								
		if($inclprivate == false)
			$sql .= " AND f.public='y'";
			
		return DB::get_results($sql);
	}
	
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