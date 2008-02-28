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
		ksort($modules);
		
		return $modules;
	}
	
	function SendEmail($email, $subject, $message)
	{
	
		$headers = "From: ".SITE_NAME." <".ADMIN_EMAIL.">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$boundary = uniqid("VDAYRSVP");
		$headers .= "Content-Type: multipart/alternative" .
		"; boundary = $boundary\r\n\r\n";
		$headers .= "This is a MIME encoded message.\r\n\r\n";
		//plain text version of message
		$headers .= "--$boundary\r\n" .
		"Content-Type: text/plain; charset=ISO-8859-1\r\n" .
		"Content-Transfer-Encoding: base64\r\n\r\n";
		$headers .= chunk_split(base64_encode($message));

		//HTML version of message
		$headers .= "--$boundary\r\n" .
					"Content-Type: text/html; charset=ISO-8859-1\r\n" .
					"Content-Transfer-Encoding: base64\r\n\r\n";
		$headers .= chunk_split(base64_encode($message));

		mail($email, $subject, '', $headers);    
	}
}
?>