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
 * @package module_admin_settings
 */
 
class Settings extends CodonModule
{

	function HTMLHead()
	{
		switch($this->get->admin)
		{
			case 'settings':
		
				Template::Set('sidebar', 'sidebar_settings.tpl');
				break;
		
			case 'customfields':
				Template::Set('sidebar', 'sidebar_customfields.tpl');
				break;
		}
		
	}
	function Controller()
	{
	
		switch($this->get->admin)
		{
			case 'settings':
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
			
				break;
		
			/* CustomFields Section
			 */
			 
			// Show the popup
			case 'addfield':
				Template::Show('settings_addcustomfield.tpl');
				break;
				
				
			case 'customfields':
		
				switch(Vars::POST('action'))
				{
					case 'savefields':
						$this->SaveFields();
						break;
						
					case 'addfield':
						$this->AddField();
						break;
						
					case 'deletefield':
						$this->DeleteField();
						break;
				}
				
				$this->ShowFields();
				
				break;
		}
	}
		
	function SaveSettings()
	{
		while(list($name, $value) = each($_POST))
		{
			if($name == 'action') continue;
			elseif($name == 'submit') continue;
			
			$value = DB::escape($value);
			SettingsData::SaveSetting($name, $value, '', false);
		}
		
		Template::Set('message', 'Settings were saved!');
		Template::Show('core_message.tpl');
	}
	
	function AddField()
	{
		if($this->post->title == '')
		{
			echo 'No field name entered!';
			return;
		}
		
		$title = $this->post->title;
		$fieldtype = $this->post->fieldtype;
		$public = $this->post->public;
		$showinregistration = $this->post->showinregistration;
		
		if($public == 'yes')
			$public = true;
		else
			$public = false;
			
		if($showinregistration == 'yes')
			$showinregistration = true;
		else
			$showinregistratino = false;
		
		if(SettingsData::AddField($title, $fieldtype, $public, $showinregistration))
			Template::Set('message', 'Settings were saved!');
		else
			Template::Set('message', 'There was an error saving the settings: ' . DB::$err);
					
		Template::Show('core_message.tpl');
	}
	
	function SaveFields()
	{
		
		print_r($_POST);
		
	}
	
	function DeleteField()
	{
		$id = DB::escape($this->post->id);
		
		if(SettingsData::DeleteField($id) == true)
		{
			Template::Set('message', 'The field was deleted');
		}
		else
		{
			Template::Set('message', 'There was an error deleting the field: ' . DB::$err);
		}

		Template::Show('core_message.tpl');
	}
	
	function ShowSettings()
	{
		Template::Set('allsettings', SettingsData::GetAllSettings());
		Template::ShowTemplate('settings_mainform.tpl');
	}
	
	function ShowFields()
	{
		Template::Set('allfields', SettingsData::GetAllFields());
		
		Template::ShowTemplate('settings_customfieldsform.tpl');
	}
}
?>