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

class FSFK extends CodonModule
{
	
	public function __call($name, $args)
	{
		$this->log(print_r($_REQUEST, true), 'acars');
		$this->log(serialize($_REQUEST), 'acars');
	}
	
	public function index()
	{
		
	}
	
	
	/**
	 * File PIREP
	 *
	 */
	public function pirep()
	{
		$this->log(print_r($_REQUEST, true), 'acars');
		$this->log(serialize($_REQUEST), 'acars');
	}
	
	/**
	 * Process ACARS messages here
	 * 
	 */
	public function acars()
	{
		$this->log(print_r($_REQUEST, true), 'acars');
		$this->log(serialize($_REQUEST), 'acars');
		
		if (!isset($_REQUEST['DATA1'])) die("0|Invalid Data");
		if (!isset($_REQUEST['DATA1'])) die("0|Invalid Data");
		
		// TEST, BEGINFLIGHT, PAUSEFLIGHT, ENDFLIGHT and MESSAGE
		$method = strtoupper($_REQUEST['DATA2']);
		
		if (!isset($_REQUEST['DATA3']))
			$value = '';
		else
			$value = $_REQUEST['DATA3'];
		
		if (!isset($_REQUEST['DATA4'])) 
			$message = '';
		else
			$message = $_REQUEST['DATA4'];
			
			
		# Go through each method now
		if($method == 'TEST')
		{
			$pilot_id = $value;
			
			echo '1|30';
			return;
		}
		elseif($method == 'UPDATEFLIGHTPLAN')
		{
			$flight_id = $value;
		}
		elseif($method == 'BEGINFLIGHT')
		{
            $flight_data = split('|', $value);
            
            if (count($flight_data) < 10) 
            {
                echo '0|Invalid login data';
                return;
            }
        }
		
	}
	
	/**
	 *  Process information for FSFK live watch
	 *
	 */
	public function livewatch()
	{
		
	}
	
	
	/**
	 * Give the user the vaconfig file to use with FSFK
	 *
	 */
	public function vaconfig_template()
	{
		$this->write_template('fsfk_vaconfig', 'VA-Template.txt');
	}
	
	public function liveacars_template()
	{
		$this->write_template('fsfk_liveacars_config', 'LiveACARS.txt');
	}
	
	public function pirep_template()
	{
		$this->write_template('fsfk_pirep_config', 'Web.txt');
	}
	
	public function email_template()
	{
		
	}
	
	public function airtv_template()
	{
		
	}
	
	protected function write_template($name, $save_as)
	{
		$this->set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
		$this->set('userinfo', Auth::$userinfo);
		
		$acars_config = Template::GetTemplate($name, true);
		$acars_config = str_replace("\n", "\r\n", $acars_config);
		
		# Set the headers so the browser things a file is being sent
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="'.$save_as.'"');
		header('Content-Length: ' . strlen($acars_config));
		
		echo $acars_config;
	}
}