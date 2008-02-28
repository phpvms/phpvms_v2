<?php
/**
 * @author phpvms.net
 * @desc Handles AJAX calls
 */

include '../core/config.inc.php';

//TODO: implementation of login-check

Template::SetTemplatePath(ADMIN_PATH . '/templates');
MainController::loadModules($ADMIN_MODULES);

//$ModuleList = Util::GetAdminModules();
//MainController::loadModules($ModuleList);

MainController::RunAllActions();

echo '<script type="text/javascript>
        EvokeListeners();
</script>';
?>