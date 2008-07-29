<?php


function pre_module_load()
{
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