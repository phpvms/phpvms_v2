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
		$sql = 'SELECT * FROM ' . TABLE_PREFIX .'users ';
		
		if($letter!='')
			$sql .= " WHERE lastname LIKE '$letter%' ";
		
		$sql .= ' ORDER BY lastname DESC';
		
		return DB::get_results($sql);
	}
	
	function GetPilotData($userid)
	{
		$sql = 'SELECT firstname, lastname, email, location, UNIX_TIMESTAMP(lastlogin) as lastlogin, 
						totalflights, totalhours, confirmed, retired
					FROM '.TABLE_PREFIX.'users WHERE userid='.$userid;
		
		return DB::get_row($sql);
	}
	
	function GetPilotByEmail($email)
	{
		$sql = 'SELECT * FROM '. TABLE_PREFIX.'users WHERE email=\''.$email.'\'';
		return DB::get_row($sql);
	}
	
	function SaveProfile($userid, $email, $location)
	{
		$sql = "UPDATE ".TABLE_PREFIX."users SET email='$email', location='$location' WHERE userid=$userid";
		
		$ret = DB::query($sql);
		
		return $ret;
	}
	
	function SaveFields($userid, $list)
	{
		$allfields = RegistrationData::GetCustomFields();
		
		foreach($allfields as $field)
		{			
			$sql = 'SELECT id FROM '.TABLE_PREFIX.'fieldvalues WHERE fieldid='.$field->fieldid.' AND userid='.$userid;
			$res = DB::get_row($sql);
		
			$value = $list[str_replace(' ', '_', $field->fieldname)];
				
			// if it exists
			if($res)
			{
				$sql = 'UPDATE '.TABLE_PREFIX.'fieldvalues 
						SET value="'.$value.'" WHERE fieldid='.$field->fieldid.' AND userid='.$userid;
			}
			else
			{
				$sql = "INSERT INTO ".TABLE_PREFIX."fieldvalues 
						(fieldid, userid, value) VALUES ($field->fieldid, $userid, '$value')";
			}
			
			DB::query($sql);
		}
	}	
	
	function GetFieldData($userid, $inclprivate=false)
	{
		$sql = 'SELECT f.fieldname, v.value 
					FROM '.TABLE_PREFIX.'customfields f 
					LEFT JOIN '.TABLE_PREFIX.'fieldvalues v
						ON f.fieldid=v.fieldid AND v.userid='.$userid;
								
		if($inclprivate == false)
			$sql .= " AND f.public='y'";
			
		return DB::get_results($sql);
	}
	
	function GetPilotGroups($userid)
	{
		$userid = DB::escape($userid);
		
		$sql = 'SELECT g.groupid, g.name
					FROM ' . TABLE_PREFIX . 'groupmembers u, ' . TABLE_PREFIX . 'groups g
					WHERE u.userid='.$userid.' AND g.groupid=u.groupid';
		
		$ret = DB::get_results($sql);
		
		return $ret;		
	}
}

?>