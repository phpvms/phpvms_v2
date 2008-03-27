<?php


class Installer
{
	
	static $error;
	
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
		
		$fp = fopen(CORE_PATH .'/site_config.inc.php', 'w');
		
		if(!$fp)
		{
			self::$error = 'There was an error opening site_config.inc.php. Please check your permissions';
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
		
	}
}
?>
