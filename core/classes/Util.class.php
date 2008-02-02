<?php
  
  
  
class Util
{

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
			
		return $modules;
	}
}
?>