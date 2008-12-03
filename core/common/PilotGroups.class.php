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
 
class PilotGroups
{
	/**
	 * Get all of the groups
	 */
	public static function GetAllGroups()
	{
		$query = 'SELECT * FROM ' . TABLE_PREFIX .'groups
						ORDER BY name ASC';
		
		return DB::get_results($query);
	}
	
	/**
	 * Add a group
	 */
	public static function AddGroup($groupname)
	{
		$query = "INSERT INTO " . TABLE_PREFIX . "groups (name) VALUES ('$groupname')";
		
		$res = DB::query($query);
				
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Get a group ID, given the name
	 */
	public static function GetGroupID($groupname)
	{
		$query = 'SELECT groupid FROM ' . TABLE_PREFIX .'groups
					WHERE name=\''.$groupname.'\'';
		
		$res = DB::get_row($query);
	
		return $res->groupid;
	}
	
	/**
	 * Add a user to a group, either supply the group ID or the name
	 */
	public static function AddUsertoGroup($pilotid, $groupidorname)
	{
		if($groupidorname == '') return false;
		
		// If group name is given, get the group ID
		if(!is_numeric($groupidorname))
		{
			$groupidorname = self::GetGroupID($groupidorname);
		}
		
		$sql = 'INSERT INTO '.TABLE_PREFIX.'groupmembers (pilotid, groupid)
					VALUES ('.$pilotid.', '.$groupidorname.')';
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Check if a user is in a group, pass the name or the id
	 */
	public static function CheckUserInGroup($pilotid, $groupid)
	{
		
		if(!is_numeric($groupidorname))
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
	
	/**
	 * The a users groups (pass their database ID)
	 */
	public static function GetUserGroups($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		
		$sql = 'SELECT g.groupid, g.name
					FROM ' . TABLE_PREFIX . 'groupmembers u, ' . TABLE_PREFIX . 'groups g
					WHERE u.pilotid='.$pilotid.' AND g.groupid=u.groupid';
		
		$ret = DB::get_results($sql);
		
		return $ret;
	}
	
	/**
	 * Remove a user from a group (pass the ID or the name)
	 */
	public static function RemoveUserFromGroup($pilotid, $groupid)
	{
		$pilotid = DB::escape($pilotid);
		$groupid = DB::escape($groupid);
		
		if(!is_numeric($groupid))
		{
			$groupid = self::GetGroupID($groupid);
		}
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groupmembers
					WHERE pilotid='.$pilotid.' AND groupid='.$groupid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Remove a group
	 */
	public static function RemoveGroup($groupid)
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