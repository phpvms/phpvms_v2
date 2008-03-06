<?php
/**
 * @author Nabeel Shahzad <www.phpvms.net>
 * @desc Admin panel home
 */
	
include '../core/config.inc.php';

if(!Auth::LoggedIn() && !Auth::UserInGroup('Administrators'))
	die('Unauthorized access!');

define('ADMIN_PANEL', true);

Template::SetTemplatePath(ADMIN_PATH . '/templates');
MainController::loadModules($ADMIN_MODULES);

$BaseTemplate = new TemplateSet;

//load the main skin
$settings_file = SITE_ROOT . '/lib/skins/crystal/crystal.php';
if(file_exists($settings_file))
	include $settings_file;
	
$BaseTemplate->template_path = SITE_ROOT . '/lib/skins/crystal';

$BaseTemplate->Set('navigation_tree', $NAVBAR);
$BaseTemplate->Set('head_text', $HTMLHead);

$BaseTemplate->Show('header.tpl');

MainController::RunAllActions();

$BaseTemplate->Show('footer.tpl');
 
?>