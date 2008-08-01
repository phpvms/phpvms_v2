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
	var $user_permissions;
		
	function CreateSalt()
	{
		return md5(uniqid(rand()));
	}
		
	/**
	 * user stuff
	 */
	 
	 //load a comprehensive list of user permissions
	 // store in the session
	function GetAllUserPermissions($userid)
	{
		$userid = DB::escape($userid);
		
		$sql = "SELECT p.categoryid, p.perms
				FROM " . APP_TABLE_PREFIX."permissions p, " . APP_TABLE_PREFIX ."users u
				WHERE u.groupid = p.groupid
					AND u.id=$userid";
					
		$res = DB::get_results($sql, ARRAY_A);
		
		if(!$res)
		{
			return false;
		}
		
		//ok we have their permissions, load a array
		$permissions_list = array();
		foreach($res as $permlist)
		{
			$permissions_list[$permlist['categoryid']] = $permlist['perms'];
		}
		
		return $permissions_list;
	}
	
	function GetAllUsers()
	{
		return DB::get_results('SELECT id, displayname, username, groupid, allowremote, lastlogin
										FROM '.APP_TABLE_PREFIX.'users
										ORDER BY username ASC');
	}
	
	function GetGroupName($groupid)
	{
		$groupid = DB::escape($groupid);
		
		$sql = 'SELECT name FROM ' . APP_TABLE_PREFIX .'groups
				 WHERE id='.$groupid;
		
		$name = DB::get_row($sql, ARRAY_A);
		return $name['name'];
	}
		
	function GetPermissionsForGroup($groupid, &$perms)
	{
		$groupid = DB::escape($groupid);
		
		$sql = 'SELECT g.groupstype AS type, p.id, p.groupid, p.categoryid, p.perms
				 FROM ' . APP_TABLE_PREFIX . 'permissions p, ' . APP_TABLE_PREFIX . 'groups g
				 WHERE p.groupid=g.id';
		
		if( is_numeric($groupid))
			$sql .= 'AND g.id='.$groupid;
		else
			$sql .= 'g.name=\''.$groupid.'\'';
							
		$perms = DB::get_results($sql, ARRAY_A);
		
		if(!$perms)
			return false;
					
		return $perms[0]['groupstype'];
	}
	
	function ChangePermissions($permid, $newperm)
	{
		$newperm = $this->ConvertPermissionsToInt($newperm);
		
		$sql = 'UPDATE ' . APP_TABLE_PREFIX . 'permissions
				 SET perms=\''.$newperm.'\'
				 WHERE id='.$permid;
	
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	function RemovePermissions($permid)
	{
		$sql = 'DELETE FROM ' . APP_TABLE_PREFIX . 'permissions
				 WHERE id='.$permid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	function GetAllGroups()
	{
		$query = 'SELECT * FROM ' . APP_TABLE_PREFIX .'groups
					ORDER BY name ASC';
		
		return DB::get_results($query);
	}
	
	function GetGroupID($groupname)
	{
		$query = 'SELECT id FROM ' . APP_TABLE_PREFIX .'groups
					WHERE name=\''.$groupname.'\'';
		
		$res = DB::get_row($query);
		
		return $res->id;
	}
	
	function GetUserInfo($username)
	{
		$username = DB::escape($username);
		$query = 'SELECT * FROM ' . APP_TABLE_PREFIX .'users
					WHERE username=\''.$username.'\'';
		
		return DB::get_results($query);
	}
	
	function CheckUserInGroup($userid, $groupid)
	{
		$query = 'SELECT g.id
				   FROM '.APP_TABLE_PREFIX.'usergroups g
				   WHERE  g.userid='.$userid.' AND g.groupid='.$groupid;
		
		return DB::get_row($query);
	}
	
	function GetGroupInfo($groupid)
	{
		$groupid = DB::escape($groupid);
		
		$query = 'SELECT * FROM ' . APP_TABLE_PREFIX . 'groups WHERE ';
		
		if(is_numeric($groupid))
			$query .= 'id='.$groupid;
		else
			$query .= 'name=\''.$groupid.'\'';
			
		return DB::get_row($query);
	}
	
	function GetUsersInGroup($groupid)
	{
		$groupid = DB::escape($groupid);
		
		//multiple groups list
		$query = 'SELECT u.id, u.displayname, u.username
					FROM '.APP_TABLE_PREFIX.'users u, '.APP_TABLE_PREFIX.'usergroups g
					WHERE g.groupid='.$groupid.' AND g.userid = u.id';
	
		return DB::get_results($query, ARRAY_A);
	}
	
	function RemoveGroup($groupid)
	{
		$groupid = DB::escape($groupid);
		
		//delete from groups table
		$sql = 'DELETE FROM '.APP_TABLE_PREFIX.'groups WHERE id='.$groupid;
		DB::query($sql);
		
		//delete from permissions table
		$sql = 'DELETE FROM '.APP_TABLE_PREFIX.'permissions WHERE groupid='.$groupid;
		DB::query($sql);
		
		//delete from usergroups table
		$sql = 'DELETE FROM '.APP_TABLE_PREFIX.'usergroups WHERE groupid='.$groupid;
		DB::query($sql);
		
		//delete from application permissions table
		$sql = 'DELETE FROM '.APP_TABLE_PREFIX.'appperms WHERE groupid='.$groupid;
		DB::query($sql);
	}
	
	function AddUser($displayname, $username, $password, $enabled=true)
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
		
		$sql = "INSERT INTO " . APP_TABLE_PREFIX ."users
						(displayname, username, password, salt, allowremote)
				VALUES ('$displayname','$username', '$password','$salt', '$enabled')";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	function AddGroup($groupname, $type)
	{
		$groupname = DB::escape($groupname);
		
		if($type != 'a' || $type != 'd')
			$type = 'd';
					
		$query = "INSERT INTO " . APP_TABLE_PREFIX . "groups (name, groupstype) VALUES ('$groupname', '$type')";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	function AddPermissions($groupid, $catid, $perm)
	{
		$sql = "INSERT INTO " . APP_TABLE_PREFIX ."permissions
					(groupid, categoryid, perms) VALUES ('$groupid', '$catid', '$perm')";
	
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	function AddUsertoGroup($userid, $groupidorname)
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
	
	function RemoveUserFromGroup($userid, $groupid)
	{
		$userid = DB::escape($userid);
		$groupid = DB::escape($groupid);
		
		$sql = 'DELETE FROM '.APP_TABLE_PREFIX.'usergroups WHERE userid='.$userid.' AND groupid='.$groupid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	function SaveGroupType($groupid, $type)
	{
		$sql = 'UPDATE '. APP_TABLE_PREFIX .'groups SET groupstype=\''.$type.'\' WHERE id='.$groupid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	function UpdateGroups(&$userlist, $groupid)
	{
		//form our query:
		$sql = 'UPDATE ' . APP_TABLE_PREFIX .'users SET groupid='.$groupid. ' WHERE ';
	
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
	
	function DeleteUser($userid)
	{
		$sql = "DELETE FROM " . APP_TABLE_PREFIX . "users WHERE id=$userid";
				
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
}
?>