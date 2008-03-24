<?php
/**
 * phpVMS Installer File
 */

include dirname(__FILE__).'/bootloader.inc.php';


Template::Show('templates/header.tpl');
// Controller

switch($_GET['page'])
{
	case '':
		
		break;
}	

Template::Show('templates/footer.tpl');
?>