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
 

class SettingsData
{
	function GetAllSettings()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX.'settings');
	}
	
	/* This is for the admin panel*/
	function GetAllFields()
	{
		$ret =  DB::get_results('SELECT * FROM '.TABLE_PREFIX.'customfields');
		
		return $ret;
	}
	
	function AddField($title, $fieldtype, $public, $showinregistration)
	{
		/*$fieldname = Vars::POST('fieldname');
		$fieldtype = Vars::POST('fieldtype');
		$public = Vars::POST('public');
		$showinregistration = Vars::POST('showinregistration');*/
		
		$fieldname = str_replace(' ', '_', $title);
		$fieldname = strtoupper($fieldname);
		
		//Check, set up like this on purpose to default "safe" values
		if($public == 'yes')
			$public = 'y';
		else
			$public = 'n';
		
		if($showinregistration == 'yes')
			$showinregistration = 'y';
		else
			$showinregistration = 'n';		
		
		$sql = "INSERT INTO " . TABLE_PREFIX ."customfields (title, fieldname, type, public, showonregister)
					VALUES ('$title', '$fieldname', '$fieldtype', '$public', '$showinregistration')";
		
		$res = DB::query($sql);	
		
		if(!$res && DB::$errno !=0)
		{			
			return false;
		}
		
		return true;		
	}
	/**
	 * Save site settings
	 *
	 * @param string $name Setting name. Must be unique
	 * @param string $value Value of the setting
	 * @param boolean $core Whether it's "vital" to the engine or not. Bascially blocks deletion
	 */
	function SaveSetting($name, $value, $descrip='', $core=false)
	{		
		if(is_bool($value))
		{
			if($value == true)
			{
				$value = 'true';
			}
			elseif($value == false)
			{
				$value = 'false';
			}
		}
		
		//see if it's an update
		if($core == true)
			$core = 't';
		else	
			$core = 'f';
			
		$name = strtoupper(DB::escape($name));
		$value = DB::escape($value);
		$descrip = DB::escape($descrip);
		
		/*$sql = 'INSERT INTO ' . TABLE_PREFIX . 'settings (name, value, descrip, core) 
					VALUES (\''.$name.'\', \''.$value.'\', \''.$descrip.'\', \''. $core.'\')';
		
		$res = DB::query($sql);
		
		if(DB::$errno == 1062 || !$res)
		{*/
			//update
			// don't change CORE status on update
			$sql = 'UPDATE ' . TABLE_PREFIX . 'settings 
						SET value=\''.$value.'\' WHERE name=\''.$name.'\'';
			
			$res = DB::query($sql);			
		//}		
		
		if(!$res && DB::$errno !=0)
		{			
			return false;
		}
		
		return true;			
	}

	/**
	 * See if the setting is part of the core
	 */	
	function IsCoreSetting($setting_name)
	{
		$sql = 'SELECT core FROM ' . TABLE_PREFIX .'settings WHERE name=\''.$setting_name.'\'';
		$res = DB::get_row($sql);
		
		if(!$res)
			return false;
			
			
		if($res->core == 't')
		{
			return true;
		}
		
		return false;		
	}
	
	function DeleteField($id)
	{
		$sql = 'DELETE FROM '.TABLE_PREFIX.'customfields WHERE fieldid='.$id;

		$res = DB::query($sql);
		
		if(!$res && DB::$errno !=0)
		{			
			return false;
		}

		return true;	

		//TODO: delete all of the field values! 
		//$sql = 'DELETE FROM '.TABLE_PREFIX.'
	}
	
	/**
	 * Delete a setting
		
	function DeleteSetting($setting_name)
	{
		$sql = 'DELETE FROM ' . TABLE_PREFIX . 'settings WHERE name=\''.$setting_name.'\'';
		$res = DB::query($sql);
		
		if($res || $this->db->errno == 0)
		{
			return true;
		}
		
		return false;
	}
	 */
}
?>