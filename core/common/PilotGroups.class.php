<?php


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
		$query = 'SELECT id FROM ' . TABLE_PREFIX .'groups 
					WHERE name=\''.$groupname.'\'';
		
		$res = DB::get_row($query);
		
		return $res->id;
	}
	
	function AddUsertoGroup($userid, $groupidorname)
	{
		if($groupidorname == '') return false;
		
		// If group name is given, get the group ID
		if(is_string($groupid))
		{
			$groupidorname = DB::escape($groupidorname);
			$groupidorname = self::GetGroupID($groupidorname);
		}
		
		$sql = 'INSERT INTO '.TABLE_PREFIX.'groupmembers (userid, groupid) 
					VALUES ('.$userid.', '.$groupidorname.')';
		
		return DB::query($sql);
	}
	
	function CheckUserInGroup($userid, $groupid)
	{
		$query = 'SELECT g.id
					FROM ' . TABLE_PREFIX . 'usergroups g
					WHERE g.userid='.$userid.' AND g.groupid='.$groupid;
		
		return DB::get_row($query);
	}
	
	function GetUserGroups($userid)
	{
		$userid = DB::escape($userid);
		
		$sql = 'SELECT g.groupid, g.name
					FROM ' . TABLE_PREFIX . 'groupmembers u, ' . TABLE_PREFIX . 'groups g
					WHERE u.userid='.$userid.' AND g.groupid=u.groupid';
		
		$ret = DB::get_results($sql);
		
		return $ret;
	}
	
	function RemoveUserFromGroup($userid, $groupid)
	{
		$userid = DB::escape($userid);
		$groupid = DB::escape($groupid);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groupmemebers
					WHERE userid='.$userid.' AND groupid='.$groupid;
		
		return DB::query($sql);
	}	
	
	function RemoveGroup($groupid)
	{
		$groupid = DB::escape($groupid);
		
		//delete from groups table
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groups WHERE id='.$groupid;	
		DB::query($sql);
				
		//delete from usergroups table
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groupmembers WHERE groupid='.$groupid;	
		DB::query($sql);
	}
	
}

?>