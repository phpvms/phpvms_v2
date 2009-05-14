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
  
class UserGroups
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
	
	public static function check_permission($set, $perm)
	{
		if(($set & $perm) === $perm)
		{
			return true;
		}
		
		return false;
	}
	
	public static function set_permission($set, $perm)
	{
		return $set | $perm;
	}
	
	public static function remove_permission($set, $perm)
	{
		$set = $set ^ $perm;		
	}
	
	public static function GetUserInfo($username)
	{
		$username = DB::escape($username);
		$query = 'SELECT * FROM ' . TABLE_PREFIX .'users
					WHERE username=\''.$username.'\'';
		
		return DB::get_results($query);
	}
	
	public static function CheckUserInGroup($userid, $groupid)
	{
		$query = 'SELECT g.id
				   FROM '.TABLE_PREFIX.'usergroups g
				   WHERE  g.userid='.$userid.' AND g.groupid='.$groupid;
		
		return DB::get_row($query);
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
	
	public static function GetUsersInGroup($groupid)
	{
		$groupid = DB::escape($groupid);
		
		//multiple groups list
		$query = 'SELECT u.id, u.displayname, u.username
					FROM '.TABLE_PREFIX.'users u, '.TABLE_PREFIX.'usergroups g
					WHERE g.groupid='.$groupid.' AND g.userid = u.id';
	
		return DB::get_results($query);
	}
		
	public static function AddUser($displayname, $username, $password, $enabled=true)
	{
		$displayname = DB::escape($displayname);
		$username = DB::escape($username);
		
		$salt =  self::CreateSalt();
		$password = md5($password . $salt);
		
		if($enabled == 'on' || $enabled == true)
		{
			$enabled = 't';
		}
		else {
			$enabled = 'f';
		}
		
		$sql = "INSERT INTO " . TABLE_PREFIX ."users
						(displayname, username, password, salt, allowremote)
				VALUES ('$displayname','$username', '$password','$salt', '$enabled')";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
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
	
	public static function AddUsertoGroup($userid, $groupidorname)
	{
		if($groupidorname == '') return false;
		
		// If group name is given, get the group ID
		if(is_string($groupid))
		{
			$groupidorname = DB::escape($groupidorname);
			$groupidorname = $this->GetGroupID($groupidorname);
		}
		
		$sql = 'INSERT INTO '.APP_TABLE_PREFIX.'usergroups (userid, groupid)
					VALUES ('.$userid.', '.$groupidorname.')';
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public static function RemoveUserFromGroup($userid, $groupid)
	{
		$userid = DB::escape($userid);
		$groupid = DB::escape($groupid);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'usergroups 
					WHERE userid='.$userid.' AND groupid='.$groupid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
		
	public static function UpdateGroups(&$userlist, $groupid)
	{
		//form our query:
		$sql = 'UPDATE ' . TABLE_PREFIX .'users SET groupid='.$groupid. ' WHERE ';
	
		$total = count($userlist);
		for($i=0;$i<$total;$i++)
		{
			$sql .= ' id='.$userlist[$i];
			if($i!=$total-1)
				$sql.= ' OR ';
		}
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public static function DeleteUser($userid)
	{
		$sql = "DELETE FROM " . TABLE_PREFIX . "users 
				WHERE id=$userid";
				
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
}
?>