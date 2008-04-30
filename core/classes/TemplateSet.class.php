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

/**
 * LiveFrame - www.nsslive.net
 *	
 * TemplateManager
 *	Handles a template set for an application
 *	Non-static methods. Declare for each ModuleBase made
 * 
 * revision updates:
 *	0 - Added
 */
 
class TemplateSet 
{
	public $template_path = '';
	public $enable_caching = false;
	public $cache_timeout = CACHE_TIMEOUT;
	
	protected $vars = array();
	
	/*public function __construct($path='')
	{
		if($path!='')
			$this->Set($path);
	}*/
	
	public function SetTemplatePath($path)
	{
		$this->template_path = $path;
	}
	
	public function EnableCaching($bool=true)
	{
		$this->enable_caching = $bool;
	}
	
	public function ClearVars()
	{
		$this->vars = array();
	}
	
	public function Set($name, $value)
	{
		// See if they're setting the template as a file
		//	Check if the file exists 
		if(!is_object($value) && strstr($value, '.'))
		{
			if(file_exists($this->template_path . '/' . $value))
				$value = $this->GetTemplate($this->template_path . '/' . $value, true);
			
		}
		
		$this->vars[$name] = $value;
	}
	
	public function Show($tpl_name)
	{
		return $this->ShowTemplate($tpl_name);
	}
	
	public function ShowTemplate($tpl_name)
	{		
		if($this->enable_caching == true)
		{
			$cached_file = CACHE_PATH . '/' . $tpl_name;
			
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
			return $this->GetTemplate($tpl_name);
		}
	}
	
	//get the actual template text
	public function GetTemplate($tpl_name, $ret=false)
	{
		
		/* See if the file has been over-rided in the skin directory
		 */
		 		 
		if(!defined('ADMIN_PANEL'))
		{
			if(file_exists(SKINS_PATH . '/' . $tpl_name))
				$tpl_path = SKINS_PATH . '/' . $tpl_name;
			else
				$tpl_path = $this->template_path . '/' . $tpl_name;
		}
		else
		{
			$tpl_path = $this->template_path . '/' . $tpl_name;
		}

		if(!file_exists($tpl_path))
		{
			trigger_error('The template file "'.$tpl_name.'" doesn\'t exist');
			return;
		}
			
		extract($this->vars, EXTR_OVERWRITE);

		ob_start();
		include $tpl_path; 
		$cont = ob_get_contents();
		ob_end_clean();
		
		//dont return, just output
		if($ret==false)
			echo $cont;
		else
			return $cont;
	}
	
	public function ShowModule($ModuleName, $Method='ShowTemplate')
	{
		$ModuleName = strtoupper($ModuleName);
		global $$ModuleName;
		
		// have a reference to the self 
		if(!is_object($$ModuleName) || ! method_exists($$ModuleName, $MethodName))
		{
			return false;	
		}
		
		// if there are parameters added, then call the function 
		//	using those additional params
		$args = func_num_args();
		if($args>2)
		{
			$vals=array();
			for($i=2;$i<$args;$i++)
			{
				$param = func_get_arg($i);
				array_push($vals, $param);
			}
			
			return call_user_method_array($MethodName,  $$ModuleName, $vals);
		}
		else
		{
			//no parameters, straight return
			return $$ModuleName->$MethodName();
		}
	}
}
?>