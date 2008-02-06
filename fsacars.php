<?php

/* Interface for FSACARS
 */
 
 
include 'core/config.inc.php';


if(count($_GET) == 0)
	die('No data');
	
MainController::loadModules($ACTIVE_MODULES);


ACARSData::InsertACARSData();

$phase = Vars::GET('phase');

//TODO: convert these phases to constants
if($phase >= 7)
{
	//file PIREP on landing, seems like the part to file?
	//TODO: work out logistics of PIREPS
	
}
	

?>