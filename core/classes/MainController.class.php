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
	private static $listSize;
	private static $keys = array();	
	
	public static $page_title;
	
	public static function loadEngineTasks()
	{		
		CodonRewrite::ProcessRewrite();
		Vars::setParameters();
		@ob_end_clean();
		/**
		 * load the modules from the modules, or the list.
		 */
		$modules = array();
				
		if(Config::Get('MODULES_AUTOLOAD') == true)
		{
			$modules = self::getModulesFromPath(Config::Get('MODULES_PATH'));
		}
		else 
		{
			if(!is_array(Config::Get('MODULE_LIST')))
			{
				Debug::showCritical('No modules found to run!');
			}
			
			// If they specified the list, build it:
			$list = array();	
			
			foreach($module_list as $key => $value)
			{
				# If they provide just a list, or include the entire path
				#	in Name=>Path format
				if(is_numeric($key))
				{
					$path = Config::Get('MODULE_PATH').DS.$module_list[$key].DS.$module_list[$i].'.php';
					$modules[$module_list[$i]] = $path;
				}
				else
				{
					$modules[$key] = Config::Get('MODULE_PATH').DS.$value;
				}
			}			
		}
		
		// See what our default module is:		
		if(Config::Get('RUN_SINGLE_MODULE') == true)
		{
			$module = CodonRewrite::$current_module;
			Config::Set('RUN_MODULE', strtoupper($module));
		}
		
		self::loadModules($modules);		
		Config::LoadSettings();
	}
	
	/**
	 * Load any PHP files which are in the core/common folder
	 */
	public static function loadCommonFolder()
	{
		$files = scandir(COMMON_PATH);

		foreach($files as $file)
		{
    		if($file == '.' || $file == '..') continue;
    		
			if(strstr($file, '.php') !== false)
				include_once COMMON_PATH.DIRECTORY_SEPARATOR.$file;
		}
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
					$fullpath = $path . DS . $file . DS . $file . '.php';
					
					if(file_exists($fullpath))
					{
						$file = strtoupper($file);
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
		self::$listSize = sizeof(self::$ModuleList);
		self::$keys = array_keys(self::$ModuleList);
		
		for ($i=0; $i<self::$listSize; $i++)
		{
			$ModuleName = self::$keys[$i];
			$ModuleController = self::$ModuleList[$ModuleName];
			
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
					
					if(Config::Get('RUN_MODULE') == $ModuleName)
					{
						# Skip it for now, run it last since it's the active
						#	one, and may overwrite some other parameters
						continue;
					}
					else
					{
						ob_start();
						self::Run($ModuleName, 'NavBar');
						$NAVBAR .= ob_get_clean();
						//ob_end_clean();
						
						//ob_start();
						self::Run($ModuleName, 'HTMLHead');
						$HTMLHead .= ob_get_clean();
						
						@ob_end_clean();
					}
				}
			}
		}
			
		# Run the init tasks
		ob_start();
		self::Run(Config::Get('RUN_MODULE'), 'NavBar');
		$NAVBAR .= ob_get_clean();
		//ob_end_clean();
		
		//ob_start();
		self::Run(Config::Get('RUN_MODULE'), 'HTMLHead');
		$HTMLHead .= ob_get_clean();
		
		@ob_end_clean();
	}
	
	/**
	 * This runs the Controller() function of all the
	 * 	modules, and gives priority to the module passed
	 *	in the parameter
	 *
	 * @param string $module_priority Module that is called first
	 * 
	 * Change - Oct 2009
	 *	Makes this more "cake-esque" - check if the "Controller" function
	 *	exists (for backwards compat), if it doesn't then run the function
	 *	defined by the "action" bit in the URL
	 */
	public static function RunAllActions($module_priority='')
	{
		if(Config::Get('RUN_SINGLE_MODULE') === true)
		{
			$call_function = 'Controller';
			$ModuleName = strtoupper(Config::Get('RUN_MODULE'));
			global $$ModuleName;
			
			// Make sure this module is valid
			if(!is_object($$ModuleName))
			{	
				Debug::showCritical("The module \"{$ModuleName}\" doesn't exist!");
				return;
			}
			
			if(!method_exists($$ModuleName, $call_function))
			{
				// Check if we have a function for the page we are calling
				//$name = $$ModuleName->get->page;
				$name = CodonRewrite::$current_action;
				if($name == '')
				{
					$call_function = 'index';
				}
				else
				{
					$call_function = $name;
				}
			}
						
			/*if(!method_exists($$ModuleName, $call_function))
			{
				Debug::showCritical("Function \"{$call_function}()\" in module {$ModuleName} doesn't exist!");
				return;
			}*/
			
			/* Don't call self::Run() - parameters could change. They have to stay the same
				due to the fact that outside modules, etc will still use Run(), so it has
				to stay the same */
			
			$ret = call_user_method_array($call_function, $$ModuleName, CodonRewrite::$params);
			
			/* Set the title, based on what the module has, if it's blank,
				then just set it to the module name */
			self::$page_title = $$ModuleName->title;
			if(self::$page_title == '')
			{
				self::$page_title = ucwords(strtolower($ModuleName));
			}
		
			//self::Run($ModuleName, $call_function, CodonRewrite::$peices);
		}
		/*else
		{
			for ($i=0; $i<self::$listSize; $i++)
			{
				$ModuleName = self::$keys[$i];				
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
		}*/
		
		return true;	
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