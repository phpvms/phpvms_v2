<?php
  
/**
 * @desc Admin panel home
 */
	
include '../core/config.inc.php';

$ModuleList = Util::GetAdminModules();
MainController::loadModules($ModuleList);

//header

MainController::RunAllActions();

//footer
 
?>