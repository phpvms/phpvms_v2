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
 
 
# phpVMS Updater 

define('ADMIN_PANEL', true);
define('UPDATE_VERSION', '1.1.<<REVISION>>');

######################################
###
# Nothing under here needs changing
###
######################################

include '../core/codon.config.php';

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
function sql_file_update($filename)
{
	# Table changes, other SQL updates
	$sql_file = file_get_contents($filename);

	for($i=0;$i<strlen($sql_file);$i++)
	{
		$str = $sql_file{$i};
		
		if($str == ';')
		{
			$sql.=$str;
			
			$sql = str_replace('phpvms_', TABLE_PREFIX, $sql);
			
			DB::query($sql);
			
			if(DB::errno() != 0 && DB::errno() != 1060)
			{
				echo '<p style="border-top: solid 1px #000; border-bottom: solid 1px #000; padding: 5px;">
						There was an error, with the following message: <br /><br />
						<span style="margin: 10px;"><i>"'.DB::error().' ('.DB::errno().')"</i></span><br /><br />
						On the following query: <br /><br />
						<span style="margin: 10px;"><i>'.$sql.'</i></span><br /><br />
						Try running it manually<br />
						</p>';
			}
			
			$sql = '';
		}
		else
		{
			$sql.=$str;
		}
	}

}

/**
 * Add an entry into the local.config.php file
 */
function add_to_config($name, $value)
{
	$config = file_get_contents(CORE_PATH.'/local.config.php');
	
	# Replace the closing PHP tag, don't need a closing tag
	$config = str_replace('?>', '', $config);
	
	# If it exists, don't add it
	if(strpos($config, $name) !== false)
	{
		return false;
	}
	
	if($name == 'BLANK')
	{
		$config = $config.'
';
	}
	else 
	{
		$config = $config ."
Config::Set('$name', ";

		if($value == true)
			$config .= "true";
		elseif($value == false)
			$config .= "false";
		else
			$config .="'$value'";
		
		$config .=");";
	}

	file_put_contents(CORE_PATH.'/local.config.php', $config);
}

// Do the queries:
echo 'Starting the update...<br />';


# Do updates based on version
#	But cascade the updates

switch(PHPVMS_VERSION)
{
	case '1.0.370': # Update to 1.1.400
	
		sql_file_update(SITE_ROOT . '/install/update_400.sql');
		add_to_config('UNITS', 'mi');
		
	case '1.1.400':
	
		sql_file_update(SITE_ROOT . '/install/update.sql');
		
		echo 'Adding some options to your config file...';
		
		add_to_config('MAP_CENTER_LAT', '45.484400');
		add_to_config('MAP_CENTER_LNG', '-62.334821');
		add_to_config('ACARS_DEBUG', false);
		add_to_config('SIGNATURE_SHOW_EARNINGS', true);
		add_to_config('SIGNATURE_SHOW_RANK_IMAGE', true);
		add_to_config('BLANK', '');
		add_to_config('AVATAR_FILE_SIZE', 50000);
		add_to_config('AVATAR_MAX_WIDTH', 80);
		add_to_config('AVATAR_MAX_HEIGHT', 80);
		
		
		break;
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