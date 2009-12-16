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

class SettingsData extends CodonData
{
	public static function GetAllSettings()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX.'settings');
	}
	
	public static function GetSetting($name)
	{
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'settings 
					WHERE name=\''.$name.'\'');
	}
	
	public static function GetSettingValue($name)
	{
		$ret = DB::get_row('SELECT value FROM '.TABLE_PREFIX.'settings 
					WHERE name=\''.$name.'\'');
					
		return $ret->value;
	}
	
	
	/**
	 * Return all of the custom fields data
	 */
	public static function GetAllFields()
	{
		return DB::get_results('SELECT * FROM '.TABLE_PREFIX.'customfields');
	}
	
	
	public static function GetField($fieldid)
	{
		$fieldid = intval($fieldid);
		return DB::get_row('SELECT * FROM '.TABLE_PREFIX.'customfields WHERE fieldid='.$fieldid);
	}
		
	/**
	 * Edit a custom field to be used in a profile
	 * 
	 * $data= array('fieldid'=>,
					 'title'=>,
					 'value'=>,
					 'type'=>,
					 'public'=>,
					 'showinregistration'=>);
	 */
	public static function AddField($data)
	{
		$fieldname = str_replace(' ', '_', $data['title']);
		$fieldname = strtoupper($fieldname);
		
		//Check, set up like this on purpose to default "safe" values
		if($data['public'] == true)
			$data['public'] = 1;
		else
			$data['public'] = 0;
		
		if($data['showinregistration'] == true)
			$data['showinregistration'] = 1;
		else
			$data['showinregistration'] = 0;
			
		$data['type'] = strtolower($data['type']);
		
		$sql = "INSERT INTO " . TABLE_PREFIX ."customfields (title, fieldname, value, type, public, showonregister)
					VALUES ('{$data['title']}', '$fieldname', '{$data['value']}', '{$data['type']}', {$data['public']}, {$data['showinregistration']})";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Edit a custom field to be used in a profile
	 * 
	 * $data= array('fieldid'=>,
					 'title'=>,
					 'value'=>,
					 'type'=>,
					 'public'=>,
					 'showinregistration'=>);
	 */
	public static function EditField($data)
	{				 
					 
		$fieldname = strtoupper(str_replace(' ', '_', $data['title']));
		
		//Check, set up like this on purpose to default "safe" values
		if($data['public'] == true)
			$data['public'] = 1;
		else
			$data['public'] = 0;
		
		if($data['showinregistration'] == true)
			$data['showinregistration'] = 1;
		else
			$data['showinregistration'] = 0;
		
		$data['type'] = strtolower($data['type']);
		
		$sql = "UPDATE " . TABLE_PREFIX ."customfields
				SET title='{$data['title']}', fieldname='{$fieldname}', value='{$data['value']}',
					type='{$data['type']}', public={$data['public']}, 
					showonregister={$data['showinregistration']}
				WHERE fieldid={$data['fieldid']}";
		
		$res = DB::query($sql);
		DB::debug();
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Save site settings
	 *
	 * @param string $name Setting name. Must be unique
	 * @param string $value Value of the setting
	 * @param boolean $core Whether it's "vital" to the engine or not. Bascially blocks deletion
	 */
	public static function SaveSetting($name, $value, $descrip='', $core=false)
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
		
		/*$sql = 'SELECT * FROM '.TABLE_PREFIX.'settings
					WHERE name=\''.$name.'\'';
		
		$res = DB::get_row($sql);
		
		if(!$res)
		{
			$sql = 'INSERT INTO ' . TABLE_PREFIX . 'settings (name, value, descrip, core)
						VALUES (\''.$name.'\', \''.$value.'\', \''.$descrip.'\', \''. $core.'\')';
		}
		else
		{*/
			//update
			// don't change CORE status on update
			$sql = 'UPDATE ' . TABLE_PREFIX . 'settings
						SET value=\''.$value.'\' WHERE name=\''.$name.'\'';
		//}
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}

	/**
	 * See if the setting is part of the core
	 */
	public static function IsCoreSetting($setting_name)
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
	
	public static function DeleteField($id)
	{
		$sql = 'DELETE FROM '.TABLE_PREFIX.'customfields WHERE fieldid='.$id;

		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;

		//TODO: delete all of the field values!
		//$sql = 'DELETE FROM '.TABLE_PREFIX.'
	}
}
?>