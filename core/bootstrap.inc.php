<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

function pre_module_load()
{
	if(ADMIN_PANEL == true && ADMIN_PANEL != 'ADMIN_PANEL')
	{
		MainController::$ModuleList = array();
		Config::Set('RUN_SINGLE_MODULE', false, true);
		Config::Set('DEFAULT_MODULE', 'Dashboard', true);
		Config::Set('URL_REWRITE', array());
	}
	
	if(!file_exists(CORE_PATH.'/local.config.php')|| filesize(CORE_PATH.'/local.config.php') == 0)
	{
		header('Location: install/install.php');
	}
	
	Auth::StartAuth();
	SiteData::loadSiteSettings();
}

function post_module_load()
{
	return;
}
?>