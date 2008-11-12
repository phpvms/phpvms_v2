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

class ACARS extends CodonModule
{
		
	public function Controller()
	{
		switch($this->get->page)		
		{
			case '':
			case 'viewmap':
				
				// fancy
				
				// Show the main ACARS map with all the positions, etc
				Template::Set('acarsdata', ACARSData::GetACARSData(Config::Get('ACARS_LIVE_TIME')));
				Template::Show('acarsmap.tpl');
				
				break;
				
			case 'acarsdata':
			
				header("Content-type: text/xml"); 
				echo ACARSData::GenerateXML();
				
				break;
				
			case 'fsacarsconfig':
				
				if(!Auth::LoggedIn())
				{
					echo 'You are not logged in!';
					break;
				}
				
				Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
				Template::Set('userinfo', Auth::$userinfo);
				$fsacars_config = Template::GetTemplate('fsacars_config.tpl', true);
				
				header('Content-Type: text/plain');
				header('Content-Disposition: attachment; filename="'.Auth::$userinfo->code.'.ini"');
				header('Content-Length: ' . strlen($fsacars_config));
				
				echo $fsacars_config;
				
			// default handles the connectors as plugins
			default:
				
				if(file_exists(CORE_PATH.'/modules/ACARS/'.$this->get->page.'.php'))
				{
					include_once CORE_PATH.'/modules/ACARS/'.$this->get->page.'.php';
					return;
				}
				
				
				
				break;	
		}
	}
		
	public function viewMap()
	{
	
	}
}

?>