<?php

/**
  * Authentication Module
  *  Handle local login (from a form) and remote login
  * phpVMS
  *
  * @author Nabeel Shahzad
  */
  
class Auth  
{
	public static $userid;
	public static $username, $password;
	public static $displayname;
	public static $usergroups;
	public static $loggedin=false;
	public static $login_error;
	public static $init=false;
	public static $error_message;
	
	/**
		* Constructor.  
		*
		* @param 
		* @return 
		*/
	function StartAuth() 
	{	
		if(SessionManager::GetData('loggedin')==true)
		{
			self::$loggedin = true;
			self::$userdata = SessionManager::GetData('userinfo');
			self::$usergroups = SessionManager::UserGroups('usergroups');
			
			self::$init = true;
			return true;
		}
		else
		{
			self::$loggedin = false;
			return false;
		}
	}
	
	function Username()
	{
		return self::$userinfo->username;
	}
	
	function DisplayName()
	{
		return self::$userinfo->displayname;
	}
	
	function LoggedIn()
	{
		if(self::$init == false)
			return self::StartAuth();
			
		return self::$loggedin;
	}
	
	function UserInGroup($groupname)
	{
		if(!self::LoggedIn()) return false;
		
		foreach(self::$usergroups as $group)
		{
			if($group->name == $groupname)
				return true;
		}
		
		return false;
	}
	
	function ProcessLogin($username, $password)
	{
		$username = DB::escape($username);
		$password = DB::escape($password);
		
		$sql = 'SELECT id, firstname, lastname, username, email, password, salt 
					FROM ' . APP_TABLE_PREFIX . 'users
					WHERE username=\''.$username.'\'';

		$userinfo = DB::get_row($sql);

		if(!is_array($userinfo))
		{
			self::$error_message = 'User does not exist';
			return false;
		}

		//ok now check it
		$hash = md5($password . $userinfo->salt);
		
		if($hash == $userinfo->password)
		{	
			SessionManager::AddData('loggedin', true);	
			SessionManager::AddData('userinfo', $userinfo);
			SessionManager::AddData('usergroups', self::GetUsersGroups(self::$userid));
			
			return true;
		}			
		else 
		{
			// just blank it
			self::$error_message = 'Invalid Password';
			
			self::LogOut();
			
			return false;
		}
	}
		
	function GetUsersGroups($userid)
	{
		$userid = DB::escape($userid);

		$sql = 'SELECT g.id, g.name, g.groupstype 
					FROM '.APP_TABLE_PREFIX.'usergroups u, '.APP_TABLE_PREFIX.'groups g
					WHERE u.userid='.$userid.' AND g.id=u.groupid';

		$ret = DB::get_results($sql);

		return $ret;
	}
	
	function LogOut()
	{
		SessionManager::AddData('userinfo', '');
		SessionManager::AddData('usergroups', '');
		
		self::$loggedin = false;
	}
}
?>