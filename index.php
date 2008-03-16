<?php

/**
 * LiveFrame - www.nsslive.net
 *	
 *  Main Index file for LiveFrameX
 * 
 * revision updates:
 *	5 -	BaseTemplate path moved here (@ 21)
 */
 
/*$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$start = $time;*/

include 'core/config.inc.php';

ksort($ACTIVE_MODULES); 

//load our modules
MainController::loadModules($ACTIVE_MODULES);

$BaseTemplate = new TemplateSet;

//load the main skin
$settings_file = SKINS_PATH . '/' . CURRENT_SKIN . '.php';
if(file_exists($settings_file))
	include $settings_file;
	
$BaseTemplate->template_path = SKINS_PATH;

Template::Set('MODULE_NAV_INC', $NAVBAR);
Template::Set('MODULE_HEAD_INC', $HTMLHead);
//$BaseTemplate->Set('navigation_tree', $NAVBAR);
//$BaseTemplate->Set('head_text', $HTMLHead);

$BaseTemplate->ShowTemplate('header.tpl');

MainController::RunAllActions();

$BaseTemplate->ShowTemplate('footer.tpl');

/*$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$finish = $time;
$totaltime = ($finish - $start);

echo '<div style="clear: both; background: #FFF; width: auto; font-size: 10px">
		<span style="color: black">This page took ', $totaltime ,' seconds to load
	  </div>';*/
?>