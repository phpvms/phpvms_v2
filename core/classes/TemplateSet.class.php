<?php

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
		if(strstr($value, '.'))
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
		if($this->enable_caching ==true)
		{
			$cached_file = CACHE_PATH . '/' . $tpl_name . md5($tpl_name);
			
			//expired?
			if((time() - filemtime($cached_file)) > ($this->cache_timeout*3600))
			{
				unlink($cached_file);
				
				$tpl_output = $this->GetTemplate($tpl_name, true);
				
				echo $tpl_output;
				
				//cache it into the storage file
				if($this->enable_caching == true || self::$enable_caching == true)
				{
					$fp = fopen($cached_file, 'w');
					fwrite($fp, $tpl_output, strlen($tpl_output));
					fclose($fp);			
				}
			}
			else
			{
				@include $cached_file;
			}
		}
		else
		{
			return $this->GetTemplate($tpl_path);
		}
	}
	
	//get the actual template text
	public function GetTemplate($tpl_name, $ret=false)
	{
		
		/* See if the file has been over-rided in the skin directory
		 */	
		 	
		if(!defined(ADMIN_PANEL))
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
		
		extract($this->vars, EXTR_OVERWRITE);
		
		ob_start();
		@include $tpl_path; 
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
		//read the parameters
		global $$ModuleName;
		
		if(!is_object($$ModuleName))
			return;
		
		$args = func_num_args();
		if($args>1)
		{
			$vals=array();
			for($i=2;$i<$args;$i++)
			{
				$param = func_get_arg($i);
				array_push($vals, $param);
			}

			return call_user_method_array($Method,  $$ModuleName, $vals);
		}
	}
}
?>