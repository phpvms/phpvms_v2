<?php

//$test->addTestCase(new InstallTester);


class InstallTester extends UnitTestCase
{
	
	public function __construct() 
	{
		parent::__construct();
		$this->UnitTestCase('Install Verification');
	}
	
	public function testInstall()
	{
		
		$tables = array('awards', 'awardsgranted', 'fuelprices',
			'updates', 'downloads', 'expenses',
			'financedata', 'acarsdata', 'airlines',
			'aircraft', 'airports', 'schedules',
			'news', 'pages', 'ranks', 'pilots',
			'pireps', 'pirepcomments', 'customfields',
			'fieldvalues', 'groups', 'groupmembers',
			'pirepfields', 'pirepvalues', 'bids',
			'settings');
		
		$installed_tables = array();
		$res = DB::get_results("SHOW TABLES");
		
		foreach($res as $row)
		{
			$installed_tables[] = $row->Tables_in_phpvms;
		}
		
		foreach($tables as $table)
		{
			$table = TABLE_PREFIX.$table;
			$this->assertTrue(in_array($table, $installed_tables), $table);
		}
		
		unset($tables);
		unset($installed_tables);
		unset($res);
		
		echo '<br />';
		
	}
	
	public function testSettings()
	{
		
		$settings = array('PHPVMS_VERSION', 'SITE_NAME', 'ADMIN_EMAIL',
			'DATE_FORMAT', 'NOTIFY_UPDATE', 'CURRENT_SKIN',
			'GOOGLE_KEY', 'DEFAULT_GROUP', 'PHPVMS_API_KEY');
		
		$existing_settings = array();
		
		$allsettings = DB::get_results("SELECT * FROM ".TABLE_PREFIX."settings");
		foreach($allsettings as $setting)
		{
			$existing_settings[] = $setting->name;			
		}
		
		foreach($settings as $setting)
		{
			$this->assertTrue(in_array($setting, $existing_settings), $setting);
		}
		
		echo '<br />';
	}
	
	public function testSettingsDoSave()
	{
		$val = SettingsData::GetSettingValue('PHPVMS_VERSION');
		$this->assertTrue($val);
		
		$save = SettingsData::SaveSetting('PHPVMS_VERSION', $val);
		$this->assertTrue($val, DB::$error);
		echo '<br />';
	}
}