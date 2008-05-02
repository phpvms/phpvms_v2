<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 *  This program is free software; you can redistribute it and/or modify it 
 *  under the terms of the GNU General Public License as published by the Free 
 *	Software Foundation; either version 2 of the License, or (at your option) 
 *	any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT 
 *  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 *	FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with 
 *	this program; if not, write to the:
 *		Free Software Foundation, Inc., 
 *		59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license GNU Public License http://opensource.org/licenses/gpl-license.php
 * @package module_admin_settings
 */
 
class SettingsAdmin
{	
	function Controller()
	{	
	
		switch(Vars::GET('admin'))
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
		if(Vars::POST('title') == '')
		{
			echo 'No field name entered!';
			return;
		}
		
		$title = Vars::POST('title');
		$fieldtype = Vars::POST('fieldtype');
		$public = Vars::POST('public');
		$showinregistration = Vars::POST('showinregistration');
		
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
		$id = DB::escape(Vars::POST('id'));
		
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