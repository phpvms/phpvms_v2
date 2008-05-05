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
 * @package core_api
 */
 
class PilotGroups
{
	function GetAllGroups()
	{
		$query = 'SELECT * FROM ' . TABLE_PREFIX .'groups 
						ORDER BY name ASC';
		
		return DB::get_results($query);
	}	
	
	function AddGroup($groupname)
	{
		$query = "INSERT INTO " . TABLE_PREFIX . "groups (name) VALUES ('$groupname')";	
		
		return DB::query($query);
	}
	
	function GetGroupID($groupname)
	{
		$query = 'SELECT groupid FROM ' . TABLE_PREFIX .'groups 
					WHERE name=\''.$groupname.'\'';
		
		$res = DB::get_row($query);
	
		return $res->groupid;
	}
	
	function AddUsertoGroup($pilotid, $groupidorname)
	{
		if($groupidorname == '') return false;
		
		// If group name is given, get the group ID
		if(preg_match('`^[0-9]+$`',$groupid) != true)
		{
			$groupidorname = self::GetGroupID($groupidorname);
		}
		
		$sql = 'INSERT INTO '.TABLE_PREFIX.'groupmembers (pilotid, groupid) 
					VALUES ('.$pilotid.', '.$groupidorname.')';
		
		return DB::query($sql);
	}
	
	function CheckUserInGroup($pilotid, $groupid)
	{
		
		if(preg_match('`^[0-9]+$`',$groupid) != true)
		{
			$groupid = self::GetGroupID($groupid);
		}
		
		$query = 'SELECT g.groupid
					FROM ' . TABLE_PREFIX . 'groupmembers g
					WHERE g.pilotid='.$pilotid.' AND g.groupid='.$groupid;
					
		if(!DB::get_row($query))
			return false;
		else	
			return true;
	}
	
	function GetUserGroups($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		
		$sql = 'SELECT g.groupid, g.name
					FROM ' . TABLE_PREFIX . 'groupmembers u, ' . TABLE_PREFIX . 'groups g
					WHERE u.pilotid='.$pilotid.' AND g.groupid=u.groupid';
		
		$ret = DB::get_results($sql);
		
		return $ret;
	}
	
	function RemoveUserFromGroup($pilotid, $groupid)
	{
		$pilotid = DB::escape($pilotid);
		$groupid = DB::escape($groupid);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groupmembers
					WHERE pilotid='.$pilotid.' AND groupid='.$groupid;
		
		return DB::query($sql);
	}	
	
	function RemoveGroup($groupid)
	{
		$groupid = DB::escape($groupid);
		
		//delete from groups table
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groups WHERE groupid='.$groupid;	
		DB::query($sql);
				
		//delete from usergroups table
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groupmembers WHERE groupid='.$groupid;	
		DB::query($sql);
	}
	
}

?>