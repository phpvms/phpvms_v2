<?php

/**
 * This file handles any misc tasks that need to be done.
 * Loaded at the very end
 */
 
class CronAdmin
{
	
	function Controller()
	{
		/*
		 * Check for updates
		 */
		switch($_GET['admin'])
		{
			case '':
				if(NOTIFY_UPDATE == true)
				{
					$postversion = @file_get_contents('http://www.phpvms.net/version.php');
					
					if(trim($postversion) != PHPVMS_VERSION && $postversion !== false)
					{
						Template::Set('message', 'An update for phpVMS is available!');
						Template::Show('core_error.tpl');
					}
				}
				
				break;
				
			case 'about':
				
				Template::Show('core_about.tpl');
				
				break;
		}
	}
}
?>