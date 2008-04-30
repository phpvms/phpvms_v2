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