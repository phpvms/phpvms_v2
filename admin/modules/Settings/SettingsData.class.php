<?php


class SettingsData
{
	function GetAllSettings()
	{
		return DB::get_results('SELECT * FROM ' . TABLE_PREFIX.'settings');
	}
	
	function GetAllFieldsForRegister()
	{
		return DB::get_results('SELECT * FROM '.TABLE_PREFIX.'customfields WHERE showonregister=y');
	}
	
	function AddField()
	{
		
		$fieldname = Vars::POST('fieldname');
		$fieldtype = Vars::POST('fieldtype');
		$public = Vars::POST('public');
		$showinregistration = Vars::POST('showinregistration');
		
		//Check, set up like this on purpose to default "safe" values
		if($public == 'yes')
			$public = 'y';
		else
			$public = 'n';
		
		if($showinregistration == 'yes')
			$showinregistration = 'y';
		else
			$showinregistration = 'n';		
		
		$sql = "INSERT INTO " . TABLE_PREFIX ."customfields (fieldname, type, public, showonregister)
					VALUES ('$fieldname', '$fieldtype', '$public', '$showinregistration')";
		
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
	
	/**
	 * Delete a setting
	 */	
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
	
}
?>