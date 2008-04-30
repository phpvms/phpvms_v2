<?php
/**
 * @author Nabeel Shahzad <www.phpvms.net>
 * @desc Handles AJAX calls
 */

include '../core/codon.config.php';

if(!Auth::LoggedIn() && !Auth::UserInGroup('Administrators'))
        die('Unauthorized access!');

define('ADMIN_PANEL', true);

Template::SetTemplatePath(ADMIN_PATH . '/templates');
MainController::loadModules($ADMIN_MODULES);

MainController::RunAllActions();

if($_GET['format'] != 'json')
	echo '<script type="text/javascript>
			EvokeListeners();
	</script>';
?>