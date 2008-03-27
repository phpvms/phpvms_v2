<?php
/**
 * phpVMS Installer File
 */

include dirname(__FILE__).'/bootloader.inc.php';

if($_POST['action'] == 'submitdb')
{
	//dbname] => [dbpass] => [dbuser] => [dbtype] => mysql [tableprefix] => phpvms_ [siteurl] => www.phpvms.net/test/ [action] => submitdb [
	
	if($_POST['DBASE_NAME'] == '' || $_POST['DBASE_USER'] == '' || $_POST['DBASE_PASS'] == '' || $_POST['DBASE_TYPE'] == ''
			|| $_POST['DBASE_SERVER'] == '' || $_POST['SITE_URL'] == '')
	{
		Template::Set('message', 'You must fill out all the required fields');
	}
	else
	{
		if(!Installer::WriteConfig())
		{
			Template::Set('message', Installer::$error);
		}
		else
		{
			if(!Installer::AddTables())
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

// Controller
switch($_GET['page'])
{
	case 'dbsetup':
	case '':
		
		Template::Show('s1_db_setup.tpl');
		
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
		}
		
		break;
}	

Template::Show('footer.tpl');
?>