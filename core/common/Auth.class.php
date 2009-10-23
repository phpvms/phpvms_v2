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

class Auth extends CodonData
{
	public static $init=false;
	public static $loggedin=false;
	public static $error_message;
	
	public static $pilotid;
	public static $userinfo;
	public static $session_id;
	public static $usergroups;
	
	/**
	 * Start the "auth engine", see if anyone is logged in and grab their info
	 *
	 * @return mixed This is the return value description
	 *
	 */
	public static function StartAuth()
	{	
		self::$init = true;

		/* Check if they're logged in */
		if(SessionManager::GetData('loggedin') == true)
		{
			self::$loggedin = true;
			self::$userinfo = SessionManager::GetData('userinfo');
			self::$usergroups = PilotGroups::GetUserGroups(self::$userinfo->pilotid);
			self::$pilotid = self::$userinfo->pilotid;
			
			# Bugfix, in case user updates their profile info, grab the latest
			self::$userinfo = PilotData::GetPilotData(self::$pilotid);

			return true;
		}
		else
		{			   
			# Load cookie data
			if($_COOKIE[VMS_AUTH_COOKIE] != '')
			{
				$data = explode('|', $_COOKIE[VMS_AUTH_COOKIE]);
				$session_id = $data[0];
				$pilot_id = $data[1];
				$ip_address = $data[2];

				// TODO: Determine data reliability from IP addresses marked
				$session_info = self::get_session($session_id, $pilot_id, $ip_address);
				
				if($session_info)
				{
					/* Populate session info */
					$userinfo = PilotData::GetPilotData($pilot_id);

					self::$loggedin = true;
					self::$userinfo = $userinfo;
					self::$pilotid = self::$userinfo->pilotid;
					self::$usergroups = SessionManager::GetData('usergroups');
					
					if(self::$usergroups == '')
					{
						self::$usergroups = PilotGroups::GetUserGroups($userinfo->pilotid);
					}
					
					SessionManager::AddData('loggedin', true);
					SessionManager::AddData('userinfo', $userinfo);
					SessionManager::AddData('usergroups', self::$usergroups);
					PilotData::UpdateLogin($userinfo->pilotid);
					self::set_session($userinfo->pilotid);
				}
			}
			else
			{
				self::$loggedin = false;
				return false;
			}
		}
		
	}

	public static function set_session($pilot_id/*, $remember=false*/)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX."sessions
				WHERE pilotid = '{$pilot_id}'";

		$session_data = DB::get_row($sql);
		if($session_data)
		{
			$sql = 'UPDATE '.TABLE_PREFIX."sessions
				    SET logintime=NOW(), ipaddress='{$_SERVER['REMOTE_ADDR']}'
				    WHERE pilotid={$pilot_id}";
			
			DB::query($sql);
			$session_id = $session_data->id;
		}
		else
		{
			$sql = "INSERT INTO ".TABLE_PREFIX."sessions
				   (pilotid, ipaddress, logintime)
				   VALUES ({$pilot_id},'{$_SERVER['REMOTE_ADDR']}', NOW())";

			DB::query($sql);
			$session_id = DB::$insert_id;
			self::$session_id = $session_id;
		}

		return $session_id;
	}

	public static function get_session($session_id, $pilot_id, $ip_address)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX."sessions
				WHERE id = '{$session_id}' AND pilotid = '{$pilot_id}'
			   "; //AND ipaddress = '{$ip_address}'

		$results = DB::get_row($sql);
		return $results;
	}

	public static function remove_sessions($pilot_id)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."sessions
			WHERE pilotid={$pilot_id}";
		DB::query($sql);
	}
	
	/**
	 * Return the pilot ID of the currently logged in user
	 *
	 * @return int The pilot's ID
	 *
	 */
	public static function PilotID()
	{
		return self::$userinfo->pilotid;
	}
	
	/**
	 * Get their firstname/last name
	 */
	public static function DisplayName()
	{
		return self::$userinfo->firstname . ' ' . self::$userinfo->lastname;
	}
	
	/**
	 * Return true/false if they're logged in or not
	 */
	public static function LoggedIn()
	{
		if(self::$init == false)
		{
			return self::StartAuth();
		}
		
		return self::$loggedin;
	}
	
	/**
	 * See if a use is in a given group
	 */
	public static function UserInGroup($groupname)
	{
		if(!self::LoggedIn()) return false;
		
		if(!self::$usergroups) self::$usergroups = array();
		foreach(self::$usergroups as $group)
		{
			if($group->name == $groupname)
				return true;
		}
		
		return false;
	}
	
	/**
	 * Log the user in
	 */
	public static function ProcessLogin($useridoremail, $password)
	{
		# Allow them to login in any manner:
		#  Email: blah@blah.com
		#  Pilot ID: VMA0001, VMA 001, etc
		#  Just ID: 001
		if(is_numeric($useridoremail))
		{
			$useridoremail =  $useridoremail - intval(Config::Get('PILOTID_OFFSET'));
			$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots
				   WHERE pilotid='.$useridoremail;
		}
		else
		{
			if(preg_match('/^.*\@.*$/i', $useridoremail) > 0)
			{
				$emailaddress = DB::escape($useridoremail);
				$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pilots
					   WHERE email=\''.$useridoremail.'\'';
			} 
			
			elseif(preg_match('/^([A-Za-z]*)(.*)(\d*)/', $useridoremail, $matches)>0)
			{
				$id = trim($matches[2]);
				$id = $id - intval(Config::Get('PILOTID_OFFSET'));
				
				$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots
					   WHERE pilotid='.$id;
			}
			
			else
			{
				self::$error_message = 'Invalid user ID';
				return false;
			}
		}
		
		$password = DB::escape($password);
		$userinfo = DB::get_row($sql);

		if(!$userinfo)
		{
			self::$error_message = 'This user does not exist';
			return false;
		}
		
		if($userinfo->retired == 1)
		{
			self::$error_message = 'Your account was deactivated, please contact an admin';
			return false;
		}

		//ok now check it
		$hash = md5($password . $userinfo->salt);
		
		if($hash == $userinfo->password)
		{	
			self::$userinfo =  $userinfo;

			SessionManager::AddData('loggedin', 'true');	
			SessionManager::AddData('userinfo', $userinfo);
			SessionManager::AddData('usergroups', PilotGroups::GetUserGroups($userinfo->pilotid));
			PilotData::UpdateLogin($userinfo->pilotid);
			
			return true;
		}			
		else 
		{
			self::$error_message = 'Invalid login, please check your username and password';
			self::LogOut();
			
			return false;
		}
	}
	
	/**
	 * Log them out
	 */	
	public static function LogOut()
	{
		self::remove_sessions(SessionManager::GetValue('userinfo', 'pilotid'));

		SessionManager::AddData('loggedin', false);
		SessionManager::AddData('userinfo', '');
		SessionManager::AddData('usergroups', '');

		# Delete cookie
		setcookie(VMS_AUTH_COOKIE, false);
		
		self::$loggedin = false;
	}
}