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
 
define('ADMIN_PANEL', true);

include '../core/codon.config.php';
include dirname(__FILE__).'/Installer.class.php';

 
# phpVMS Updater 
define('UPDATE_VERSION', '1.1.<<REVISION>>');
define('REVISION', '<<REVISION>>');

Template::SetTemplatePath(SITE_ROOT.'/install/templates');
Template::Show('header.tpl');

# Check versions for mismatch, unless ?force is passed
if(!isset($_GET['force']))
{
	if(PHPVMS_VERSION == UPDATE_VERSION)
	{
		echo '<p>You already have updated! Please delete this /install folder.<br /><br />
				To force the update to run again, click: <a href="update.php?force">update.php?force</a></p>';
		exit;
	}
}

/** 
 * Run a sql file
 */
// Do the queries:
echo 'Starting the update...<br />';


# Do updates based on version
#	But cascade the updates

	$version = intval(str_replace('.', '', PHPVMS_VERSION));
	echo $version;
	if($version  < 11400)
	{
		Installer::sql_file_update(SITE_ROOT . '/install/update_400.sql');
		Installer::add_to_config('UNITS', 'mi');
	}
	elseif($version <  11428)
	{
		Installer::sql_file_update(SITE_ROOT . '/install/update_437.sql');
		
		echo 'Adding some options to your config file...';
		
		Installer::add_to_config('MAP_CENTER_LAT', '45.484400');
		Installer::add_to_config('MAP_CENTER_LNG', '-62.334821');
		Installer::add_to_config('ACARS_DEBUG', false);
		Installer::add_to_config('SIGNATURE_SHOW_EARNINGS', true);
		Installer::add_to_config('SIGNATURE_SHOW_RANK_IMAGE', true);
		Installer::add_to_config('BLANK', '');
		Installer::add_to_config('AVATAR_FILE_SIZE', 50000);
		Installer::add_to_config('AVATAR_MAX_WIDTH', 80);
		Installer::add_to_config('AVATAR_MAX_HEIGHT', 80);
	}
	elseif($version < 11441)
	{
		Installer::sql_file_update(SITE_ROOT . '/install/update_11441.sql');
	}
	else
	{
		Installer::add_to_config('PAGE_ENCODING', 'ISO-8859-1', 'This is the page encoding');
		Installer::add_to_config('PILOTID_LENGTH', 4, 'This is the length of the pilot ID. including leading zeros');
		Installer::add_to_config('SIGNATURE_TEXT_COLOR', '#FFF');
		Installer::add_to_config('SIGNATURE_SHOW_COPYRIGHT', true);
	}
	
# Final version update
$sql = 'UPDATE `phpvms_settings` 
			SET value=\''.UPDATE_VERSION.'\' 
			WHERE name=\'PHPVMS_VERSION\'';
			
DB::query($sql);

echo '<p><strong>Update completed!</strong><br />
		If there were any errors, please correct them, and re-run the update using: <a href="update.php?force">update.php?force</a></p>';

Template::Show('footer.tpl');
?>