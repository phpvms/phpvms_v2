<?php

/* Just enter the module name
*/

//Module/Folder_Name => Name of Controller file

//$ACTIVE_MODULES['TestModule'] = MODULES_PATH . '/TestModule/TestModuleController.php';
$ACTIVE_MODULES['Contact'] = MODULES_PATH . '/Contact/Contact.php';

//what skin to use
define('SITE_URL', 'http://www.phpvms.net/test');
define('CURRENT_SKIN', 'default');

define('PREFIX', 'phpvms_');

//database info
define('DBASE_USER', 'nssliven_phpvms');
define('DBASE_PASS', 'a1b2c3');
define('DBASE_NAME', 'nssliven_phpvms');
define('DBASE_SERVER', 'localhost'); 

?>