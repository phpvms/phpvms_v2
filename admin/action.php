<?php
/**
 * @author phpvms.net
 * @desc Handles AJAX calls
 */

include '../core/config.inc.php';


//TODO: implementation of login-check

$ModuleList = Util::GetAdminModules();
MainController::loadModules($ModuleList);

MainController::RunAllActions();

?>