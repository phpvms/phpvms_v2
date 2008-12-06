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
				
			case 'pirepfields':
				Template::Set('sidebar', 'sidebar_pirepfields.tpl');
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
				
				Template::Set('title', 'Add Field');
				Template::Set('action', 'addfield');
				
				Template::Show('settings_addcustomfield.tpl');
				break;
				
			case 'editfield':
				
				Template::Set('title', 'Edit Field');
				Template::Set('action', 'savefield');
				Template::Set('field', SettingsData::GetField($this->get->id));
				
				Template::Show('settings_addcustomfield.tpl');
				
				break;
				
			case 'addpirepfield':
				
				Template::Set('title', 'Add PIREP Field');
				Template::Set('action', 'addfield');
				Template::Show('settings_addpirepfield.tpl');
				
				break;
				
			case 'editpirepfield':
				
				Template::Set('title', 'Edit PIREP Field');
				Template::Set('action', 'savefields');
				Template::Set('field', PIREPData::GetFieldInfo($this->get->id));
				
				Template::Show('settings_addpirepfield.tpl');
				
				break;
				
			case 'pirepfields':
				
				switch(Vars::POST('action'))
				{
					case 'savefields':
						$this->PIREP_SaveFields();
						break;
						
					case 'addfield':
						$this->PIREP_AddField();
						break;
						
					case 'deletefield':
						$this->PIREP_DeleteField();
						break;
				}
				
				$this->PIREP_ShowFields();
				
				break;
				
			case 'customfields':
		
				switch(Vars::POST('action'))
				{
					case 'savefield':
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
			
			DB::debug();
		}
		
		Template::Set('message', 'Settings were saved!');
		Template::Show('core_success.tpl');
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
			$showinregistration = false;
		
		$ret = SettingsData::AddField($title, $fieldtype, $public, $showinregistration);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error saving the settings: ' . DB::error());
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'Settings were saved!');
			Template::Show('core_success.tpl');
		}
	}
	
	function SaveFields()
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
			$showinregistration = false;
		
		$ret = SettingsData::EditField($this->post->fieldid, $title, $fieldtype, $public, $showinregistration);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error saving the settings: ' . DB::error());
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'Settings were saved!');
			Template::Show('core_success.tpl');
		}
	}
	
	function DeleteField()
	{
		$id = DB::escape($this->post->id);
		
		$ret = SettingsData::DeleteField($id) == true;
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error deleting the field: ' . DB::error());
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'The field was deleted');
			Template::Show('core_success.tpl');
		}
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
	
	function PIREP_ShowFields()
	{
		Template::Set('allfields', PIREPData::GetAllFields());
		
		Template::ShowTemplate('settings_pirepfieldsform.tpl');
	}
	
	function PIREP_AddField()
	{
		if($this->post->title == '')
		{
			echo 'No field name entered!';
			return;
		}
		
		$ret = PIREPData::AddField($this->post->title, $this->post->type, $this->post->options);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error saving the field: ' . DB::error());
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'PIREP field added!');
			Template::Show('core_success.tpl');
		}
	}
	
	function PIREP_SaveFields()
	{
		
		if($this->post->title == '')
		{
			Template::Set('message', 'The title cannot be blank');
			Template::Show('core_error.tpl');
			return false;
		}
		
		$res = PIREPData::EditField($this->post->fieldid, $this->post->title, $this->post->type, $this->post->options);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error saving the field');
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'Field saved!');
			Template::Show('core_success.tpl');
		}		
	}
	
	function PIREP_DeleteField()
	{
		$id = DB::escape($this->post->id);
		
		$ret = PIREPData::DeleteField($id);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'There was an error deleting the field: ' . DB::$err);
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', 'The field was deleted');
			Template::Show('core_success.tpl');
		}
	}
}
?>