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
				
					
			/**
			 * Output the FSACARS config file from the template
			 *	Tell the browser its <code>.ini for the airline that
			 *	the pilot is registered to
			 */
			case 'fsacarsconfig':
				
				if(!Auth::LoggedIn())
				{
					echo 'You are not logged in!';
					break;
				}
				
				Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
				Template::Set('userinfo', Auth::$userinfo);
				
				$fsacars_config = Template::GetTemplate('fsacars_config.tpl', true);
				$fsacars_config = str_replace("\n", "\r\n", $fsacars_config);
				
				# Set the headers so the browser things a file is being sent
				header('Content-Type: text/plain');
				header('Content-Disposition: attachment; filename="'.Auth::$userinfo->code.'.ini"');
				header('Content-Length: ' . strlen($fsacars_config));
				
				//error_reporting(0);
				
				echo $fsacars_config;
				
				break;
			/**
			 * Output the fsacars config
			 */
			case 'fspaxconfig':
			
				if(!Auth::LoggedIn())
				{
					echo 'You are not logged in!';
					break;
				}
				
				Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
				Template::Set('userinfo', Auth::$userinfo);
				
				$fspax_config = Template::GetTemplate('fspax_config.tpl', true);
				$fspax_config = str_replace("\n", "\r\n", $fspax_config);
				
				# Set the headers so the browser things a file is being sent
				header('Content-Type: text/plain');
				header('Content-Disposition: attachment; filename="'.Auth::$userinfo->code.'_config.cfg"');
				header('Content-Length: ' . strlen($fspax_config));
				
				echo $fspax_config;
				
				break;
				
			case 'xacarsconfig':
				
				if(!Auth::LoggedIn())
				{
					echo 'You are not logged in!';
					break;
				}
				
				Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
				Template::Set('userinfo', Auth::$userinfo);
				
				$xacars_config = Template::GetTemplate('xacars_config.tpl', true);
				$xacars_config = str_replace("\n", "\r\n", $xacars_config);
				
				# Set the headers so the browser things a file is being sent
				header('Content-Type: text/plain');
				header('Content-Disposition: attachment; filename="xacars.ini"');
				header('Content-Length: ' . strlen($xacars_config));
				
				echo $xacars_config;
				
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
	
	protected function acars_json_data()
	{
		
		$flights = ACARSData::GetACARSData();
		
		if(!$flights) 
			$flights = array();
			
		$outflights = array();
		foreach($flights as $flight)
		{	
			$c = array();
			$c['flightnum'] = $flight->flightnum;
			$c['lat'] = $flight->lat;
			$c['lng'] = $flight->lng;
			$c['pilotid'] = $flight->pilotid;
			$c['pilotname'] = $flight->pilotname;
			$c['aircraft'] = $flight->aircraftname;
			$c['depicao'] = $flight->depicao;
			$c['deplat'] = $flight->deplat;
			$c['deplng'] = $flight->deplng;
			$c['arricao'] = $flight->arricao;
			$c['arrlat'] = $flight->arrlat;
			$c['arrlng'] = $flight->arrlng;
			$c['phase'] = $flight->phase;
			$c['alt'] = $flight->alt;
			$c['gs'] = $flight->gs;
			$c['distremain'] = $flight->distremain;
			$c['timremain'] = $flight->timeremain;
			
			$outflights[] = $c;
			
			continue;
		}
		
		echo json_encode($outflights);
		
	}
}
?>