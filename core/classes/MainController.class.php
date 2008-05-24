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
 * @package codon
 */
 
class MainController
{
	public static $ModuleList = array();
	public static $DB;
	public static $activeModule;
	
	public static function loadModulesFromPath($path)
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
						$modules[$file] = $fullpath;
				}
		    }
		}
		
		closedir($dh);		

		self::loadModules($modules);
	}
	
	public static function loadModules(&$ModuleList)
	{
		global $ACTIVE_MODULES;
		global $NAVBAR;
		global $HTMLHead;
						
		self::$ModuleList = $ModuleList;
		
		//load each module and initilize
		foreach(self::$ModuleList as $ModuleName => $ModuleController)
		{	
			//formulate proper module path
			//$mpath = MODULES_PATH . '/' . $ModuleName . '/'.$ModuleController;
			$mpath = $ModuleController;
		
			if(file_exists($ModuleController))
			{
				include_once $ModuleController;
				
				if(class_exists($ModuleName))
				{
					$ModuleName = strtoupper($ModuleName);
					global $$ModuleName;
					
					self::$activeModule = $ModuleName;
				
					$$ModuleName = new $ModuleName();
					
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
	
	public static function RunAllActions()
	{
		//priority with specific module, call the rest later
		$PModule = '';
		if($_GET['module']!='')
		{
			$PModule = stripslashes($_GET['module']);
			
			//make sure the module exists, it's not just some bogus
			// name they passed in
			if(self::valid_module($PModule))
			{
				//it's valid, so do all the stuff for it				
				self::Run($PModule, 'Controller');
				self::$activeModule = $PModule;
			}
		}
		
		//check if a module is defined
		foreach(self::$ModuleList as $ModuleName => $ModuleController) 
		{
			//skip over it if we called it already
			if($ModuleName == $PModule)
				continue;
							
			self::Run($ModuleName, 'Controller');
		}
	}
	
	/* Made public, can also call other modules reliably this way
		r6 - Variable function parameters added
		r5 - Code reworked
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
	
	/* 
	  seperate function because it will be expanded with API
		later on when the install routines, etc are included
		just makes sure the module is a valid one in the module's list
	*/
	private static function valid_module($Module)
	{
		if(self::$ModuleList[$Module] != '')
			return true;			
	}
	
	public static function getModuleList()
	{
		return self::$ModuleList;
	}	
}
?>