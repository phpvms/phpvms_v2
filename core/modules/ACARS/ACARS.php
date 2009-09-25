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

function writedebug($msg)
{
	$debug = Config::Get('ACARS_DEBUG');
	
	/*if(!$debug)
		return;*/
	
	$fp = fopen(dirname(__FILE__).'/log.txt', 'a+');
	$msg .= '
			';
	
	fwrite($fp, $msg, strlen($msg));
	
	fclose($fp);
}

class ACARS extends CodonModule
{
		
	public function Controller()
	{
		switch($this->get->page)		
		{
			#
			# Just view the generic ACARS map
			#
			case '':
			case 'viewmap':
				
				// fancy
				
				// Show the main ACARS map with all the positions, etc
				Template::Set('acarsdata', ACARSData::GetACARSData(Config::Get('ACARS_LIVE_TIME')));
				Template::Show('acarsmap.tpl');
				
				break;
				
			case 'data':
			
				$this->acars_json_data();
				
				break;
				
			case 'routeinfo':
				$this->routeinfo();
				
				break;				
					
			/**
			 * Output the FSACARS config file from the template
			 *	Tell the browser its <code>.ini for the airline that
			 *	the pilot is registered to
			 */
			
			/* cleaned up @revision 744 */
			case 'fsacarsconfig':
			
				$this->write_config('fsacars_config.tpl', Auth::$userinfo->code.'.ini');
				
				break;
			/**
			 * Output the fsacars config
			 */
			case 'fspaxconfig':
			
				$this->write_config('fspax_config.tpl', Auth::$userinfo->code.'_config.cfg');
				
				break;
				
			case 'xacarsconfig':
			
				$this->write_config('xacars_config.tpl', 'xacars.ini');
				
				break;
				
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
	
	
	/**
	 * Write out a config file to the user, give the template name and
	 *	the filename to save the template as to the user
	 *
	 * @param mixed $template_name Template to use for config (fspax_config.tpl)
	 * @param mixed $save_as File to save as (xacars.ini)
	 * @return mixed Nothing, sends the file to the user
	 *
	 */
	protected function write_config($template_name, $save_as)
	{
		if(!Auth::LoggedIn())
		{
			echo 'You are not logged in!';
			break;
		}
		
		Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
		Template::Set('userinfo', Auth::$userinfo);
		
		$acars_config = Template::GetTemplate($template_name, true);
		$acars_config = str_replace("\n", "\r\n", $acars_config);
		
		# Set the headers so the browser things a file is being sent
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="'.$save_as.'"');
		header('Content-Length: ' . strlen($acars_config));
		
		echo $acars_config;
		
	}
	
	protected function acars_json_data()
	{
		
		$flights = ACARSData::GetACARSData();
		
		if(!$flights) 
			$flights = array();
			
		$outflights = array();
		foreach($flights as $flight)
		{	
			$c = (array) $flight; // Convert the object to an array
			
			$c['pilotid'] = PilotData::GetPilotCode($c['code'], $c['pilotid']);
						
			$outflights[] = $c;
			
			continue;
		}
		
		echo json_encode($outflights);
		
	}
	
	protected function routeinfo()
	{		
		if($this->get->depicao == '' || $this->get->arricao == '')
			return;
		
		$depinfo = OperationsData::GetAirportInfo($this->get->depicao);
		if(!$depinfo)
		{
			$depinfo = OperationsData::RetrieveAirportInfo($this->get->depicao);
		}
		
		$arrinfo = OperationsData::GetAirportInfo($this->get->arricao);
		if(!$arrinfo)
		{
			$arrinfo = OperationsData::RetrieveAirportInfo($this->get->arricao);
		}
		
		// Convert to json format
		$c = array();
		$c['depapt'] = (array) $depinfo;
		$c['arrapt'] = (array) $arrinfo;
		
		echo json_encode($c);
	}
}