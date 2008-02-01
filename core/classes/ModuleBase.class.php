<?php
/**
 * LiveFrame - www.nsslive.net
 *	
 * ModuleBase Class
 *	Base class for all of the modules
 * 
 * revision updates:
 *	5 - Changed $DB reference to point to DB class object
 *		activeModule points to proper place
 */

class ModuleBase
{
	protected $init=false;
	protected $TEMPLATE;
	protected $TPL;
	protected $DB;
	public static $activeModule;
	
	//manually also call contruction, used in MainController
	function __construct()
	{	
		$this->ParentInit();
	}
	
	function ParentInit()
	{
		$this->TEMPLATE = new TemplateSet;
		$this->init = true;
		$this->activeModule = MainController::$activeModule;
		
		//global objects
		$this->DB = &DB::$DB; // &$DB;
		$this->TPL = $this->TEMPLATE;
	}
}
?>