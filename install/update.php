<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
/**
 * phpVMS updater file
 */
define('ADMIN_PANEL', true);
include '../core/codon.config.php';

define('UPDATE_VERSION', '1.0.361');

// Check versions for mismatch, unless ?force is passed

if(!isset($_GET['force']))
{
	if(PHPVMS_VERSION == UPDATE_VERSION)
	{
		echo 'You already have updated! Please delete this /install folder';
		exit;
	}
}

$sql = array();

// Table changes, other SQL updates
$sql[] = 'ALTER TABLE `phpvms_schedules` ADD `notes` TEXT NOT NULL';
$sql[] = 'ALTER TABLE `phpvms_schedules` ADD `enabled` INT NOT NULL DEFAULT \'1\'';

// Version update
$sql[] = 'UPDATE `phpvms_settings` SET value=\''.UPDATE_VERSION.'\' WHERE name=\'PHPVMS_VERSION\'';


Template::SetTemplatePath(SITE_ROOT.'/install/templates');
Template::Show('header.tpl');
// Do the queries:
echo "Starting the update...<br />
	  Running SQL table updates...<br />";

foreach($sql as $query)
{
	// replace the table prefix with the 'proper' one from the settings
	$query = str_replace('phpvms_', TABLE_PREFIX, $query);
	DB::query($query);
	
	if(DB::errno() != 0)
	{
		echo '<p style="border-top: solid 1px #000; border-bottom: solid 1px #000; padding: 5px;">
				There was an error, with the following message: <br /><br />
					<span style="margin: 10px;"><i>"'.DB::error().' ('.DB::errno().')"</i></span><br /><br />
				On the following query: <br /><br />
					<span style="margin: 10px;"><i>'.$query.'</i></span><br /><br />
				Try running it manually<br />
			  </p>';
	}
}

echo '<strong>Update completed!</strong>
		If there were any errors, please correct them, and re-run the update using: <a href="update.php?force">update.php?force</a>';

Template::Show('footer.tpl');
?>