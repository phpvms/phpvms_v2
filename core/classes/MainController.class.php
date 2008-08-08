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
 
class MainController
{
	public static $ModuleList = array();
	public static $activeModule;
	private static $stop_execute = false;
	
	
	public static function loadEngineTasks()
	{
		Vars::URLRewrite(Config::Get('URL_REWRITE'));
		
		/**
		 * load the modules from the modules, or the list.
		 */
		$modules = array();
		
		if(Config::Get('MODULES_AUTOLOAD') == true)
		{
			$modules = self::getModulesFromPath(MODULES_PATH);
		}
		
		$module_list = Config::Get('ACTIVE_MODULES');
		$count = count($module_list);
		if($count > 0)
		{
			// If they specified the list, build it:
			$list = array();
			for($i=0; $i<$count; $i++)
			foreach($module_list as $key => $value)
			{
				if(is_numeric($key))
				{
					$path = MODULES_PATH . '/' . $module_list[$key] . '/' . $module_list[$i] .'.php';
					$modules[$module_list[$i]] = $path;
				}
				else
				{
					$modules[$key] = MODULES_PATH . '/' . $value;
				}
			}
		}
		
		self::loadModules($modules);
					
		/*if(Config::Get('RUN_SINGLE_MODULE') == true
			&& in_array('modules', Config::Get('URL_REWRITE')) == false)
		{
			// Throw a mismatch error
			Debug::ThrowFatal('RUN_SINGLE_MODULE is true, but you don\'t have a module variable set in your rewrite sc')
		}*/
		
		// See what our default module is:
		
		if(Config::Get('RUN_SINGLE_MODULE') == true)
		{
			$module = Vars::GET('module');
			
			if($module == '') // No module specified, so run the default
			{
				if(Config::Get('DEFAULT_MODULE') == '')
				{
					trigger_error('No Default module has been specified!
									Please correct this in app.config.php', E_USER_ERROR);
				}
				
				Config::Add('RUN_MODULE', Config::Get('DEFAULT_MODULE'));
			}
			else
			{
				Config::Add('RUN_MODULE', $module);
			}
		}
				
		Config::LoadSettings();
	}
	
	/**
	 * Load any PHP files which are in the core/common folder
	 */
	public static function loadCommonFolder()
	{
		$dh = opendir(COMMON_PATH);
		$modules = array();
				
		while (($file = readdir($dh)) !== false)
		{
		    if($file != "." && $file != "..")
		    {
		    	if(substr($file, strlen($file)-4, 4) == '.php')
		    	{
					include_once COMMON_PATH.'/'.$file;
				}
		    }
		}
		
		closedir($dh);
	}
	
	/**
	 * Search for any modules in the core/modules directory
	 * 	Then call loadModules() after building the list
	 *
	 * @param string $path Base folder from where to run modules
	 */
	public static function getModulesFromPath($path)
	{
		$dh = opendir($path);
		$modules = array();
				
		while (($file = readdir($dh)) !== false)
		{
		    if($file != "." && $file != "..")
		    {
		    	if(is_dir($path.'/'.$file))
		    	{
					$fullpath = $path . '/' . $file . '/' . $file . '.php';
					
					if(file_exists($fullpath))
					{
						$modules[$file] = $fullpath;
					}
						
				}
		    }
		}
		
		closedir($dh);

		return $modules;
	}
	
	/**
	 * Load and initialize any modules from a list
	 *
	 * @param array $ModuleList List of modules. $key is name, $value is path
	 */
	public static function loadModules(&$ModuleList)
	{
		global $NAVBAR;
		global $HTMLHead;
		
		self::$ModuleList = $ModuleList;
		
		Vars::setParameters();
		
		//load each module and initilize
		foreach(self::$ModuleList as $ModuleName => $ModuleController)
		{
			//formulate proper module path
			//$mpath = MODULES_PATH . '/' . $ModuleName . '/'.$ModuleController;
					
			if(file_exists($ModuleController))
			{
				include_once $ModuleController;
				
				if(class_exists($ModuleName))
				{
					$ModuleName = strtoupper($ModuleName);
					global $$ModuleName;
					
					self::$activeModule = $ModuleName;
				
					$$ModuleName = new $ModuleName();
					
					$$ModuleName->init($ModuleName); // Call the parent constructor
					
					//"Magic function" for the main navigation
					if(method_exists($$ModuleName, 'NavBar'))
					{
						ob_start();
						$$ModuleName->NavBar();
						$NAVBAR .= ob_get_clean();
						ob_end_clean();
					}
					
					//Another magic function
					if(method_exists($$ModuleName, 'HTMLHead'))
					{
						ob_start();
						$$ModuleName->HTMLHead();
						$HTMLHead .= ob_get_clean();
						ob_end_clean();
					}
				}
			}
		}
	}
	
	/**
	 * This runs the Controller() function of all the
	 * 	modules, and gives priority to the module passed
	 *	in the parameter
	 *
	 * @param string $module_priority Module that is called first
	 */
	public static function RunAllActions($module_priority='')
	{
		//priority with specific module, call the rest later
		$PModule = '';
		if($module_priority!='')
		{
			$PModule = strtoupper(stripslashes($module_priority));
			
			//make sure the module exists, it's not just some bogus
			// name they passed in
			/*if(self::valid_module($PModule))
			{*/
				//it's valid, so do all the stuff for it
				self::Run($PModule, 'Controller');
				self::$activeModule = $PModule;
			//}
			
			if(Config::Get('RUN_SINGLE_MODULE') == true)
				return true;
		}
		
		//check if a module is defined
		foreach(self::$ModuleList as $ModuleName => $ModuleController)
		{
			//skip over it if we called it already
			if($ModuleName == $PModule)
				continue;
				
			// Check if a module has called stop, if it has then abort
			if(self::$stop_execute == true)
			{
				self::$stop_execute = false;
				return true;
			}
							
			self::Run($ModuleName, 'Controller');
		}
	}
	
	/**
	 * Call a specific function in a module
	 *	Function accepts additional parameters, and then passes
	 *	those parameters to the function which is being called.
	 *
	 * @param string $ModuleName Name of the module to call
	 * @param string $MethodName Method in the module to call
	 * @return value
	 */
	public static function Run($ModuleName, $MethodName)
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
			
			//@todo: replacement, as call_user_method_array() is being depr.
			return call_user_method_array($MethodName,  $$ModuleName, $vals);
		}
		else
		{
			//no parameters, straight return
			return $$ModuleName->$MethodName();
		}
	}
	
	/**
	 * This stops execution of additional modules when
	 * 	RunAllActions() is being called. After the current
	 *	module is called, no more of them will be called
	 *	afterwards
	 */
	public static function stopExecution()
	{
		self::$stop_execute = true;
	}
	
	/**
	 * Seperate function because it will be expanded with API
	 *	later on when the install routines, etc are included
	 *	just makes sure the module is a valid one in the module's list
	 *
	 * @param string $Module See if $Module is a valid, initilized module
	 */
	protected static function valid_module($Module)
	{
		if(self::$ModuleList[$Module] != '')
			return true;
	}
	
	/**
	 * Return the list of loaded modules
	 *
	 * @return array List of active modules
	 */
	public static function getModuleList()
	{
		return self::$ModuleList;
	}
}
?>