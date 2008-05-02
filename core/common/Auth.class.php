<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 *  This program is free software; you can redistribute it and/or modify it 
 *  under the terms of the GNU General Public License as published by the Free 
 *	Software Foundation; either version 2 of the License, or (at your option) 
 *	any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT 
 *  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 *	FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with 
 *	this program; if not, write to the:
 *		Free Software Foundation, Inc., 
 *		59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license GNU Public License http://opensource.org/licenses/gpl-license.php
 * @package core_api
 */
 
class Auth  
{
	public static $init=false;
	public static $loggedin=false;
	public static $error_message;
	
	public static $pilotid;
	public static $userinfo;
	public static $usergroups;
	
	/**
	 * Constructor.  
	 *
	 * @param 
	 * @return 
	 */
	function StartAuth() 
	{	
		self::$init = true;
		
		if(SessionManager::GetData('loggedin') == 'true')
		{
			self::$loggedin = true;
			self::$userinfo = SessionManager::GetData('userinfo');
			self::$usergroups = SessionManager::GetData('usergroups');
			self::$pilotid = self::$userinfo->pilotid;
			
			self::$userinfo = PilotData::GetPilotData(self::$pilotid);
			
			//print_r(self::$userinfo);
			//print_r(self::$usergroups);
			return true;
		}
		else
		{
			self::$loggedin = false;
			return false;
		}
	}
	
	function pilotid()
	{
		return self::$userinfo->pilotid;
	}
	
	function Username()
	{
		return self::$userinfo->username;
	}
	
	function DisplayName()
	{
		return self::$userinfo->firstname . ' ' . self::$userinfo->lastname;
	}
	
	function LoggedIn()
	{
		if(self::$init == false)
		{
			return self::StartAuth();
		}
		
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
	
	function ProcessLogin($emailaddress, $password)
	{
		$emailaddress = DB::escape($emailaddress);
		$password = DB::escape($password);
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pilots
					WHERE email=\''.$emailaddress.'\'';

		$userinfo = DB::get_row($sql);

		if(!$userinfo)
		{
			self::$error_message = 'This user does not exist';
			return false;
		}

		//ok now check it
		$hash = md5($password . $userinfo->salt);
				
		if($hash == $userinfo->password)
		{	
		
			//self::$userinfo =  $userinfo;

			SessionManager::AddData('loggedin', 'true');	
			SessionManager::AddData('userinfo', $userinfo);
			SessionManager::AddData('usergroups', PilotGroups::GetUserGroups($userinfo->pilotid));
						
			return true;
		}			
		else 
		{
			self::$error_message = 'Invalid login, please check your username and password';
			self::LogOut();
			
			return false;
		}
	}
		
	function LogOut()
	{
		SessionManager::AddData('loggedin', false);
		SessionManager::AddData('userinfo', '');
		SessionManager::AddData('usergroups', '');
		
		self::$loggedin = false;
	}
}
?>