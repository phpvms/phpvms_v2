<?php


function pre_module_load()
{
	
	if(ADMIN_PANEL == true && ADMIN_PANEL != 'ADMIN_PANEL')
	{
		echo 'yea';
		Config::Add('RUN_SINGLE_MODULE', false, true);
		Config::Add('DEFAULT_MODULE', '');
		Config::Add('URL_REWRITE', array());
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