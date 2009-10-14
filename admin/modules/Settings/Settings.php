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

	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function HTMLHead()
	{
		switch($this->get->page)
		{
			case '':
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
	
	public function index()
	{
		$this->settings();
	}
	
	public function settings()
	{
		switch($this->post->action)
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
	
	
	public function addfield()
	{
		Template::Set('title', Lang::gs('settings.add.field'));
		Template::Set('action', 'addfield');
		
		Template::Show('settings_addcustomfield.tpl');
	}
	
	public function editfield()
	{
		Template::Set('title', Lang::gs('settings.edit.field'));
		Template::Set('action', 'savefield');
		Template::Set('field', SettingsData::GetField($this->get->id));
		
		Template::Show('settings_addcustomfield.tpl');
	}
	
	
	public function addpirepfield()
	{
		Template::Set('title', Lang::gs('pirep.field.add'));
		Template::Set('action', 'addfield');
		Template::Show('settings_addpirepfield.tpl');
	}
	
	public function editpirepfield()
	{
		Template::Set('title', Lang::gs('pirep.field.edit'));
		Template::Set('action', 'savefields');
		Template::Set('field', PIREPData::GetFieldInfo($this->get->id));
		
		Template::Show('settings_addpirepfield.tpl');
	}
	
	public function pirepfields()
	{
		switch($this->post->action)
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
	}
	
	public function customfields()
	{
		switch($this->post->action)
		{
			case 'savefield':
				$this->save_fields_post();
				break;
				
			case 'addfield':
				$this->add_field_post();
				break;
				
			case 'deletefield':
				$this->delete_field_post();
				break;
		}
		
		$this->ShowFields();
	}
	
	/* Utility functions */	
	
		
	protected function save_settings_post()
	{
		while(list($name, $value) = each($_POST))
		{
			if($name == 'action') continue;
			elseif($name == 'submit') continue;
			
			$value = DB::escape($value);
			SettingsData::SaveSetting($name, $value, '', false);
		
		}
		
		Template::Set('message', 'Settings were saved!');
		Template::Show('core_success.tpl');
	}
	
	protected function add_field_post()
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
	
	protected function save_fields_post()
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
	
	protected function delete_field_post()
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
	
	protected function ShowSettings()
	{
		Template::Set('allsettings', SettingsData::GetAllSettings());
		Template::ShowTemplate('settings_mainform.tpl');
	}
	
	protected function ShowFields()
	{
		Template::Set('allfields', SettingsData::GetAllFields());
		
		Template::ShowTemplate('settings_customfieldsform.tpl');
	}
	
	protected function PIREP_ShowFields()
	{
		Template::Set('allfields', PIREPData::GetAllFields());
		
		Template::ShowTemplate('settings_pirepfieldsform.tpl');
	}
	
	protected function PIREP_AddField()
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
	
	protected function PIREP_SaveFields()
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
	
	protected function PIREP_DeleteField()
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