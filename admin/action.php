<?php
/**
 * @author Nabeel Shahzad <www.phpvms.net>
 * @desc Handles AJAX calls
 */

include '../core/config.inc.php';

//TODO: implementation of login-check

define('ADMIN_PANEL', true);

Template::SetTemplatePath(ADMIN_PATH . '/templates');
MainController::loadModules($ADMIN_MODULES);

MainController::RunAllActions();

echo '<script type="text/javascript>
        EvokeListeners();
</script>';
?>