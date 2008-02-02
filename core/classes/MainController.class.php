<?php
/**
 * LiveFrame - www.nsslive.net
 *	
 * MainController
 *	Handles main task delegations
 * 
 * Revision updates:
 *  7 - loadModules now loads from list
 *  6 - Run() handles function parameters
 *	5 - run_module_action revised
 *		function names changed
 *		active module data added
 */
 
class MainController
{
	public static $ModuleList = array();
	public static $DB;
	public static $activeModule;
	
	public static function loadModules($ModuleList)
	{
		global $ACTIVE_MODULES;
		global $NAV_BAR;
		global $HTMLHead;
				
		self::$ModuleList = $ModuleList;
		
		//load each module and initilize
		foreach(self::$ModuleList as $ModuleName => $ModuleController)
		{	
			//formulate proper module path
			//$mpath = MODULES_PATH . '/' . $ModuleName . '/'.$ModuleController;
			$mpath = $ModuleController;
		
			if(file_exists($mpath))
			{
				include_once $mpath;
				
				if(class_exists($ModuleName))
				{
					$ModuleName = strtoupper($ModuleName);
					global $$ModuleName;
					
					self::$activeModule = $ModuleName;
										
					$$ModuleName = new $ModuleName();
					//call_user_method('ParentInit', $$ModuleName);
					
					//"Magic function" for the main navigation
					if(method_exists($$ModuleName, 'NavBar'))
					{
						$NAV_BAR .= $$ModuleName->NavBar();
					}
					
					//Another magic function
					if(method_exists($$ModuleName, 'HTMLHead'))
					{
						$HTMLHead .= $$ModuleName->HTMLHead();
					}
				}
			}
		}
	}
	
	public static function RunAllActions()
	{
		//priority with specific module, call the rest later
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
			return false;	
			
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