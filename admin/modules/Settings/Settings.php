<?php


include dirname(__FILE__) . '/SettingsData.class.php';

class Settings extends ModuleBase
{
		
	function NavBar()
	{
		echo '<li><a href="#">Settings</a>
					<ul>
						<li><a href="?admin=settings">General Settings</a></li>
						<li><a href="?admin=customfields">Custom Profile Fields</a></li>
					</ul>
				</li>';
	}
	
	function Controller()
	{		
		//$this->TEMPLATE->template_path = dirname(__FILE__) . '/templates';
		
		if(Vars::GET('admin') == 'settings')
		{
		
			// Check for POST here since we'll be outputting the form again
			// jQuery will replace the entire <div> with fresh updated content
		
			switch(Vars::POST('action'))
			{
				case 'addsetting':
					$this->AddSetting();
					break;
				case 'savesettings':
					$this->SaveSettings();
					break;
			}
						
			$this->ShowSettings();
		}
		
		/* CustomFields Section
		 */
		elseif(Vars::GET('admin') == 'customfields')
		{
			switch(Vars::POST('action'))
			{
				case 'savefields':
					$this->SaveFields();
					break;
					
				case 'addfield':
					$this->AddField();
					break;
			}
			
			$this->ShowFields();
		}
		
	}
		
	function SaveSettings()
	{
		while(list($name, $value) = each($_POST))
		{
			if($name == 'action') continue;
			
			$value = DB::escape($value);
			SettingsData::SaveSetting($name, $value, '', false);
		}		
	}
	
	function AddField()
	{
		if(Vars::POST('fieldname') == '')
		{
			echo 'No field name entered!';
			return;
		}
		
		SettingsData::AddField();
	}
	
	function SaveFields()
	{
		
		print_r($_POST);
		
	}
	
	function ShowSettings()
	{
		Template::Set('allsettings', SettingsData::GetAllSettings());
		
		Template::ShowTemplate('settings_mainform.tpl');
		//$this->TEMPLATE->ShowTemplate('addsetting.tpl');
	}
	
	function ShowFields()
	{
		Template::Set('allfields', SettingsData::GetAllFields());
		
		Template::ShowTemplate('settings_customfieldsform.tpl');
		Template::ShowTemplate('settings_addcustomfield.tpl');
		
	}
}
?>