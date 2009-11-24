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
 
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');

define('ADMIN_PANEL', true);
define('INSTALLER_VERSION', '1.2.##REVISION##');

include dirname(__FILE__).'/loader.inc.php';

if($_POST['action'] == 'submitdb')
{
	//dbname] => [dbpass] => [dbuser] => [dbtype] => mysql [tableprefix] => phpvms_ [siteurl] => www.phpvms.net/test/ [action] => submitdb [

	if($_POST['DBASE_NAME'] == '' || $_POST['DBASE_USER'] == '' || $_POST['DBASE_TYPE'] == ''
			|| $_POST['DBASE_SERVER'] == '' || $_POST['SITE_URL'] == '')
	{
		Template::Set('message', 'You must fill out all the required fields');
	}
	else
	{
		if(!Installer::AddTables())
		{
			Template::Set('message', Installer::$error);
		}
		else
		{
			if(!Installer::WriteConfig())
			{
				Template::Set('message', Installer::$error);
			}
			else
			{
				header("Location: install.php?page=sitesetup");
			}
		}
	}
}


Template::Show('header.tpl');

echo '<h3 align="left">phpVMS Installer</h3>';

// Controller
switch($_GET['page'])
{
	case 'dbsetup':
	case '':
		
		if(!Installer::CheckServer())
		{
			Template::Show('s0_config_check.tpl');
		}
		else
		{
			Template::Show('s1_db_setup.tpl');
		}
		
		break;
		
	case 'sitesetup':
		Template::Show('s2_site_setup.tpl');
		break;
		
	case 'complete':
		
		if($_POST['action'] == 'submitsetup')
		{
			if($_POST['SITE_NAME'] == '' || $_POST['firstname'] == '' || $_POST['lastname'] == '' 
					|| $_POST['email'] == '' ||  $_POST['password'] == '' || $_POST['vaname'] == '' 
					|| $_POST['vacode'] == '')
			{
				Template::Set('message', 'You must fill out all of the fields');
				Template::Show('s2_site_setup.tpl');
				break;
			}
				
			if(!Installer::SiteSetup())
			{
				Template::Set('message', Installer::$error);
				Template::Show('s2_site_setup.tpl');
			}
			else
			{
				Installer::RegisterInstall(INSTALLER_VERSION);
				echo '<p>Your site is all setup! You can login to the admin panel <a href="'.SITE_URL.'/admin">here</a></p>';
			}
		}
		
		break;
}	

Template::Show('footer.tpl');
?>