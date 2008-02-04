<?php
  

class Util
{

	/**
	 * Load all the site settings. Make the settings into define()'s
	 *	so they're accessible from everywhere
	 */
	function LoadSiteSettings()
	{			
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'settings';
		
		$all_settings = DB::get_results($sql);
				
		if(!$all_settings)
			return false;
		
		foreach($all_settings as $setting)
		{				
			if(!defined($setting->name))
			{		
				//correct value for booleans
				if($setting->value == 'true')
				{
					$setting->value = true;
				}
				elseif($setting->value == 'false')
				{
					$setting->value = false;
				}
				
				define($setting->name, $setting->value);
			}	
		}
	
		return false;
	}
	
	
	/**
	 * Get the names of any available skins.
	 *	Just read the folder names inside the skins folder
	 */
	function GetAvailableSkins()
	{
		$skins = array();
		$skins_dir = SITE_ROOT . '/lib/skins';
		
		if (is_dir($skins_dir)) 
		{	
			$fh = opendir($skins_dir);			
			
			while (($file = readdir($fh)) !== false) {
				
				if ($file == '.' || $file == '..') 
					continue;
				
				$filepath = $skins_dir . '/' . $file;
				$script_path = '';
				
				if(is_dir($filepath))
				{
					array_push($skins, $file);
				}
			}
			closedir($fh);
		} 
		
		return $skins;
	}	
	
	function GetAdminModules()
	{
		$modules = array();
		$modules_dir = SITE_ROOT . '/admin/modules';
		
		$dh = opendir($modules_dir);
				
		while (($file = readdir($dh)) !== false) 
		{
		    if($file != "." && $file != "..") 
		    {
		    	if(is_dir($modules_dir.'/'.$file))
		    	{
		    		$modules[$file] = $modules_dir . '/' . $file . '/' . $file . '.php';
				}
		    }
		}
		closedir($dh);
		
		//sort it in alpha order
		$title = array();
		foreach($modules as $key=>$row)
		{
			$title[$key] = $row['title'];
		}
			
		array_multisort($title, SORT_REGULAR, $modules);
		
		return $modules;
	}
}
?>