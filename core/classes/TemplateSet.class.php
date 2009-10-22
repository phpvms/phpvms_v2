<?php
/**
 * Codon PHP Framework
 *	www.nsslive.net/codon
 * Software License Agreement (BSD License)
 *
 * Copyright (c) 2008 Nabeel Shahzad, nsslive.net
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2.  Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.nsslive.net/codon
 * @license BSD License
 * @package codon_core
 */
 
class TemplateSet 
{
	public $template_path = '';
	public $enable_caching = false;
	public $cache_timeout;
	
	protected $vars = array();
	
	/*public function __construct($path='')
	{
		if($path!='')
			$this->Set($path);
	}*/
	
	/**
	 * Set the default path to look for the templates
	 * 
	 * @param string $path Path to the templates folder
	 */
	public function SetTemplatePath($path)
	{
		# Remove trailing directory separator
		$len = strlen($path);
		if($path[$len-1] == DS)
			$path=substr($path, 0, $len-1);
			
		$this->template_path = $path;
	}
	
	public function EnableCaching($bool=true)
	{
		$this->enable_caching = $bool;
	}
	
	/**
	 * Clear all variables
	 */
	public function ClearVars()
	{
		$this->vars = array();
	}
	
	/**
	 * Set a variable to the template, call in the template
	 * as $name
	 * 
	 * @param mixed $name Variable name
	 * @param mixed $value Variable value
	 */
	public function Set($name, $value)
	{
		// See if they're setting the template as a file
		//	Check if the file exists 
		if((!is_object($value) && !is_array($value)) && strstr($value, '.'))
		{
			if(file_exists($this->template_path . DS . $value))
			{
				$value = $this->GetTemplate($value, true);
			}
		}
		
		$this->vars[$name] = $value;
	}
	
	/**
	 * Alias to self::ShowTemplate();
	 * 
	 * @param string $tpl_name Template name including extention
	 * @param bool $checkskin Check the skin folder or not
	 */
	public function Show($tpl_name, $checkskin=true)
	{
		return $this->ShowTemplate($tpl_name, $checkskin);
	}
	
	
	/**
	 * Show a template on screen, checks to see if the
	 *	template is cached or not as well. To return a template,
	 *  use self::GetTemplate(); this ends up calling GetTemplate()
	 *	if the cache is empty or disabled
	 *
	 * @param string $tpl_name Template name including extention
	 * @param bool $checkskin Check the skin folder or not
	 * @return mixed This is the return value description
	 *
	 */
	public function ShowTemplate($tpl_name, $checkskin=true)
	{		
		if($this->enable_caching == true)
		{
			$cached_file = CACHE_PATH . DS . $tpl_name;
			
			// The cache has expired
			if((time() - filemtime($cached_file)) > ($this->cache_timeout*3600))
			{
				unlink($cached_file);
				
				$tpl_output = $this->GetTemplate($tpl_name, true);
				
				echo $tpl_output;
				
				//cache it into the storage file
				if($this->enable_caching == true)
				{
					$fp = fopen($cached_file, 'w');
					fwrite($fp, $tpl_output, strlen($tpl_output));
					fclose($fp);			
				}
			}
			else // Cache not expired, so just include that cache
			{
				@include $cached_file;
			}
		}
		else
		{
			return $this->GetTemplate($tpl_name,false,$checkskin);
		}
	}
	
	
	/**
	 * Alias to $this->GetTemplate()
	 *
	 * @param string $tpl_name Template to return (with extension)
	 * @param bool $ret Return the template or output it on the screen
	 * @param bool $checkskin Check the active skin folder for the template first
	 * @return mixed Returns template text is $ret is true
	 *
	 */
	public function Get($tpl_name, $ret=false, $checkskin=true)
	{
		return $this->GetTemplate($tpl_name, $ret, $checkskin);
	}
	
	/**
	 * GetTemplate
	 *  This gets the actual template data from a template, and fills
	 *	in the variables
	 *
	 * @param string $tpl_name Template to return (with extension)
	 * @param bool $ret Return the template or output it on the screen
	 * @param bool $checkskin Check the active skin folder for the template first
	 * @return mixed Returns template text is $ret is true
	 *
	 */
	public function GetTemplate($tpl_name, $ret=false, $checkskin=true)
	{
		/* See if the file has been over-rided in the skin directory
		 */

		if(!defined('ADMIN_PANEL') && $checkskin == true)
		{
			if(file_exists(SKINS_PATH . DS . $tpl_name))
				$tpl_path = SKINS_PATH . DS . $tpl_name;
			else
				$tpl_path = $this->template_path . DS . $tpl_name;
		}
		else
		{
			$tpl_path = $this->template_path . DS . $tpl_name;
		}

		if(!file_exists($tpl_path))
		{
			trigger_error('The template file "'.$tpl_path.'" doesn\'t exist');
			return;
		}
			
		extract($this->vars, EXTR_OVERWRITE);
		
		ob_start();
		include $tpl_path; 
		$cont = ob_get_contents();
		ob_end_clean();
		
		# Check if we wanna return
		if($ret==true)		
			return $cont;
			
		echo $cont;
	}
	
	
	/**
	 * ShowModule
	 *	This is an alias to MainController::Run(); calls a function
	 *	in a module. Returns back whatever the called function returns
	 *
	 * @param string $ModuleName Module name to call
	 * @param string $MethodName Function which to call in the module
	 * @return mixed This is the return value description
	 *
	 */
	public function ShowModule($ModuleName, $MethodName='ShowTemplate')
	{
		return MainController::Run($ModuleName, $MethodName);
	}
}
?>