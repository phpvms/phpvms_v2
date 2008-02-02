<?php

/* Just enter the module name
*/

//Module/Folder_Name => Name of Controller file

$ACTIVE_MODULES['TestModule'] = MODULES_PATH . '/TestModule/TestModuleController.php';
$ACTIVE_MODULES['Contact'] = MODULES_PATH . 'Contact/Contact.php';

//what skin to use
define('CURRENT_SKIN', 'default');

//database info
define('DBASE_USER', '');
define('DBASE_PASS', '');
define('DBASE_NAME', '');
define('DBASE_SERVER', 'localhost'); 

?>