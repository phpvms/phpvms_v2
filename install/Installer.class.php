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

class Installer
{
	
	static $error;
	
	public static function CheckServer()
	{
		$noerror = true;
		$version = phpversion();
		$wf = array();
		
		// These needa be writable
		$wf[] = 'core/pages';
		$wf[] = 'core/cache';
		$wf[] = 'lib/rss';
		$wf[] = 'lib/avatars';
		$wf[] = 'lib/signatures';
		
		// Check the PHP version
		if($version[0] != '5')
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
				$message = 'Could not create core/local.config.php. Create this file, blank, with write permissions.';
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
		
		// Check all of the folders for writable permissions
		$status = '';
		foreach($wf as $folder)
		{
			if(!is_writeable(SITE_ROOT.'/'.$folder))
			{
				$noerror = false;
				$type = 'error';
				$message = $folder.' is not writeable';
			}
			else
			{
				$type = 'success';
				$message = $folder.' is writeable!';
			}
			
			$status.='<div id="'.$type.'">'.$message.'</div>';
		}
		
		Template::Set('directories', $status);
		//Template::Set('pagesdir', '<div id="'.$type.'">'.$message.'</div>');
		
		return $noerror;
	}
	
	public static function WriteConfig()
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
	
	public static function AddTables()
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
							
				if(DB::errno() == 1050)
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
	
	public static function SiteSetup()
	{
		/*$_POST['SITE_NAME'] == '' || $_POST['firstname'] == '' || $_POST['lastname'] == ''
					|| $_POST['email'] == '' ||  $_POST['password'] == '' || $_POST['vaname'] == ''
					|| $_POST['vacode'] == ''*/
					
		// first add the airline
		
		$_POST['vacode'] = strtoupper($_POST['vacode']);
		if(!OperationsData::AddAirline($_POST['vacode'], $_POST['vaname']))
		{
			self::$error = DB::$error;
			return false;
		}
		
		// add the user

		if(!RegistrationData::AddUser($_POST['firstname'], $_POST['lastname'],
				$_POST['email'], $_POST['vacode'], '', '', $_POST['password'], PILOT_ACCEPTED))
		{
			self::$error = DB::$error;
			return false;
		}
		
		// add to admin group
		$pilotdata = PilotData::GetPilotByEmail($_POST['email']);

		if(!PilotGroups::AddUsertoGroup($pilotdata->pilotid, 'Administrators'))
		{
			self::$error = DB::$error;
			return false;
		}
		
		SettingsData::SaveSetting('SITE_NAME', $_POST['SITE_NAME']);
		SettingsData::SaveSetting('ADMIN_EMAIL', $_POST['email']);
		
		return true;
		
	}
	
	public static function sql_file_update($filename)
	{
		
		if(isset($_GET['test']))
			return true;
			
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
	public static function add_to_config($name, $value, $comment='')
	{
		if(isset($_GET['test']))
			return true;
			
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
			$config = $config.PHP_EOL;
		}
		elseif($name == 'COMMENT')
		{
			if(strpos($config, '# '.$value) !== false)
			{
				return false;
			}
			
			$config = $config.PHP_EOL.'#'.$value.PHP_EOL;
		}
		else 
		{
			$config = $config.PHP_EOL."Config::Set('$name', ";
			
			if(is_bool($value))
			{
				if($value === true)
					$config .= "true";
				elseif($value === false)
					$config .= "false";
			}
			else
				$config .="'$value'";
			
			$config .="); ";
			if($comment!='')
				$config .='# '.$comment;
		}
		
		file_put_contents(CORE_PATH.'/local.config.php', $config);
	}
	
	
	public static function RegisterInstall($version='')
	{
		if($version == '')
			$version = PHPVMS_VERSION;
			
		$ext = serialize(get_loaded_extensions());
		$params=array('name'=>SITE_NAME,
					  'url'=>SITE_URL,
					  'email'=>SettingsData::GetSettingValue('ADMIN_EMAIL'),
					  'version'=>$version,
					  'php'=>phpversion(),
					  'mysql'=>@mysql_get_server_info(),
					  'ext'=>$ext);
					  
		$url = 'http://update.phpvms.net/register.php';
					
		# Do fopen(), if that fails then it'll default to 
		#	curl
		$file = new CodonWebService();
		$file->setType('fopen'); 
		$response = $file->get($url, $params);
	}
}