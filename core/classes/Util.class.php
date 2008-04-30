<?php
/*
Codon PHP Framework
www.nsslive.net/codon

 Software License Agreement (BSD License)
 
 Copyright (c) 2008 Nabeel Shahzad, nsslive.net

 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions
 are met:

 1. Redistributions of source code must retain the above copyright
    notice, this list of conditions and the following disclaimer.
 2.  Redistributions in binary form must reproduce the above copyright
    notice, this list of conditions and the following disclaimer in the
    documentation and/or other materials provided with the distribution.
 3. The name of the author may not be used to endorse or promote products
    derived from this software without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/  

class Util
{

	/**
	 * Load all the site settings. Make the settings into define()'s
	 *	so they're accessible from everywhere
	 */
	function LoadSiteSettings()
	{			
		global $Config;
		
		while(list($key, $value) = each($Config))
		{
			define($key, $value);
		}
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'settings';
		
		$all_settings = DB::get_results($sql);
				
		if(!$all_settings)
			return false;
		
		foreach($all_settings as $setting)
		{				
			//if(!defined($setting->name))
			//{		
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
			//}	
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
				
				if ($file == '.' || $file == '..' || $file == '.svn') 
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
		$boundary = uniqid("PHPVMSMAILER");
		$headers .= "Content-Type: multipart/alternative" .
		"; boundary = $boundary\r\n\r\n";
		$headers .= "This is a MIME encoded message.\r\n\r\n";
		//plain text version of message
		$headers .= "--$boundary\r\n" .
		"Content-Type: text/plain; charset=ISO-8859-1\r\n" .
		"Content-Transfer-Encoding: base64\r\n\r\n";
		$headers .= chunk_split(base64_encode($message));

		//HTML version of message
		$message = nl2br($message);
		$headers .= "--$boundary\r\n" .
					"Content-Type: text/html; charset=ISO-8859-1\r\n" .
					"Content-Transfer-Encoding: base64\r\n\r\n";
		$headers .= chunk_split(base64_encode($message));

		mail($email, $subject, '', $headers);    
	}
}
?>