<?php


function pre_module_load()
{
	if(ADMIN_PANEL == true && ADMIN_PANEL != 'ADMIN_PANEL')
	{
		MainController::$ModuleList = array();
		Config::Set('RUN_SINGLE_MODULE', false, true);
		Config::Set('DEFAULT_MODULE', '', true);
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