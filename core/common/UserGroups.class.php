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
  
class UserGroups extends CodonData
{
	public $user_permissions;
		
	public static function CreateSalt()
	{
		return md5(uniqid(rand()));
	}
		
	/**
	 * user stuff
	 */
	 
	
	public static function GetAllUsers()
	{
		return DB::get_results('SELECT id, displayname, username, groupid, allowremote, lastlogin
										FROM '.TABLE_PREFIX.'users
										ORDER BY username ASC');
	}
	
	public static function GetGroupName($groupid)
	{
		$groupid = DB::escape($groupid);
		
		$sql = 'SELECT name FROM ' . TABLE_PREFIX .'groups
				 WHERE id='.$groupid;
		
		return DB::get_var($sql);
	}
		
	
	public static function GetAllGroups()
	{
		$query = 'SELECT * FROM ' . TABLE_PREFIX .'groups
					ORDER BY name ASC';
		
		return DB::get_results($query);
	}
	
	public static function GetGroupID($groupname)
	{
		$query = 'SELECT id FROM ' . TABLE_PREFIX .'groups
					WHERE name=\''.$groupname.'\'';
		
		$res = DB::get_row($query);
		
		return $res->id;
	}
	
	
	
	public static function GetGroupInfo($groupid)
	{
		$groupid = DB::escape($groupid);
		
		$query = 'SELECT * FROM ' . TABLE_PREFIX . 'groups WHERE ';
		
		if(is_numeric($groupid))
			$query .= 'id='.$groupid;
		else
			$query .= 'name=\''.$groupid.'\'';
			
		return DB::get_row($query);
	}
		
	public static function AddGroup($groupname, $type)
	{
		$groupname = DB::escape($groupname);
		
		if($type != 'a' || $type != 'd')
			$type = 'd';
					
		$query = "INSERT INTO " . TABLE_PREFIX . "groups (name, groupstype) VALUES ('$groupname', '$type')";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
}
?>