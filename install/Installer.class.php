<?php


class Installer
{
	
	static $error;
	
	function CheckServer()
	{
		$noerror = true;
		$version = phpversion();
		
		// Check the PHP version
		if(substr($version, 0, 1) != '5')
		{
			$noerror = false;
			$type = 'error';
			$message = 'You need to run PHP 5 (your version: '.$version.')';
		}	
		else
		{
			$type = 'success';
			$message = 'OK! (your version:'.$version.')';
		}
		
		Template::Set('phpversion', '<div id="'.$type.'">'.$message.'</div>');	
		
		
		
		// Check if core/site_config.inc.php is writeable
		if(!file_exists(CORE_PATH .'/local.config.php'))
		{
			if(!$fp = fopen(CORE_PATH .'/local.config.php', 'w'))
			{
				$noerror = false;
				$type = 'error';
				$message = 'Could not create core/site_config.inc.php. Create this file, blank, with write permissions.';
			}
		}
		else
		{		
			if(!is_writeable(CORE_PATH .'/local.config.php'))
			{
				$noerror = false;
				$type = 'error';
				$message = 'core/local.config.php is not writeable';
			}
			else
			{
				$type = 'success';
				$message = 'core/local.config.php is writeable!';
			}
		}
		
		Template::Set('configfile', '<div id="'.$type.'">'.$message.'</div>');
		
		
		if(!is_writeable(CORE_PATH .'/pages'))
		{
			$noerror = false;
			$type = 'error';
			$message = 'core/pages is not writeable';
		}
		else
		{
			$type = 'success';
			$message = 'core/pages is writeable!';
		}
		
		Template::Set('pagesdir', '<div id="'.$type.'">'.$message.'</div>');	
		
		return $noerror;
	}
	
	function WriteConfig()
	{
		$tpl = file_get_contents(SITE_ROOT . '/install/templates/config.tpl');
		
		$tpl = str_replace('$DBASE_USER', $_POST['DBASE_USER'], $tpl);
		$tpl = str_replace('$DBASE_PASS', $_POST['DBASE_PASS'], $tpl);
		$tpl = str_replace('$DBASE_NAME', $_POST['DBASE_NAME'], $tpl);
		$tpl = str_replace('$DBASE_SERVER', $_POST['DBASE_SERVER'], $tpl);
		$tpl = str_replace('$DBASE_TYPE', $_POST['DBASE_TYPE'], $tpl);
		$tpl = str_replace('$TABLE_PREFIX', $_POST['TABLE_PREFIX'], $tpl);
		$tpl = str_replace('$SITE_URL', $_POST['SITE_URL'], $tpl);
		
		$fp = fopen(CORE_PATH .'/local.config.php', 'w');
		
		if(!$fp)
		{
			self::$error = 'There was an error opening local.config.php. Please check your permissions';
			return false;
		}
		
		fwrite($fp, $tpl, strlen($tpl));
	
		fclose($fp);
		
		return true;
	}	
	
	function AddTables()
	{
		
		// Write the SQL Tables, from install.sql
		
		// first connect:
		
		if(!DB::init($_POST['DBASE_TYPE']))
		{
			self::$error = DB::$error;
			return false;
		}
		
		$ret = DB::connect($_POST['DBASE_USER'], $_POST['DBASE_PASS'], $_POST['DBASE_NAME'], $_POST['DBASE_SERVER']);
		
		if($ret == false)
		{
			self::$error = DB::$error;
			return false;
		}
	
		if(!DB::select($_POST['DBASE_NAME']))
		{
			self::$error = DB::$error;
			return false;
		}
		
		// 1 table at a time - read upto a ; and then
		//	run the query
		
		$sql = '';
		
		$sql_file = file_get_contents(SITE_ROOT . '/install/install.sql');
		
		for($i=0;$i<strlen($sql_file);$i++)
		{
			$str = $sql_file{$i};
			
			if($str == ';')
			{
				$sql.=$str;
				
				$sql = str_replace('phpvms_', $_POST['TABLE_PREFIX'], $sql);
				
				DB::query($sql);
				
				if(DB::$errno == 1050)
					continue;
				$sql = '';
			}	
			else
			{	
				$sql.=$str;
			}
		}
		
		return true;
	}
	
	function SiteSetup()
	{
		/*$_POST['SITE_NAME'] == '' || $_POST['firstname'] == '' || $_POST['lastname'] == '' 
					|| $_POST['email'] == '' ||  $_POST['password'] == '' || $_POST['vaname'] == '' 
					|| $_POST['vacode'] == ''*/
					
		// first add the airline
		if(!OperationsData::AddAirline($_POST['vacode'], $_POST['vaname']))
		{
			self::$error = DB::$error;
			return false;
		}
		
		// add the user

		if(!RegistrationData::AddUser($_POST['firstname'], $_POST['lastname'],
				$_POST['email'], $_POST['vacode'], '', $_POST['password'], PILOT_ACCEPTED))
		{
			self::$error = DB::$error;
			return false;
		}
		
		// add to admin group
		$pilotdata = PilotData::GetPilotByEmail($_POST['email']);

		if(!PilotGroups::AddUsertoGroup($pilotdata->pilotid, 'Administrators'))
		{
			DB::debug();
			self::$error = DB::$error;
			return false;
		}
		
		SettingsData::SaveSetting('SITE_NAME', $_POST['SITE_NAME']);
		
		return true;
		
	}
}
?>
