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
 
/* FSPassengers ACARS Interface */

	# Check for connection
	if($_POST['FsPAskConnexion'] == 'yes')
	{
		# Validate pilot:
		$_POST['UserName'] = DB::escape($_POST['UserName']);
		
		# Entered as ###
		if(is_numeric($_POST['UserName']))
		{
			$pilotid = $_POST['UserName'];
		}
		else
		{
			# Check if they entered as XXX###
			if(preg_match('/^([A-Za-z]*)(.*)(\d*)/', $_POST['UserName'], $matches)>0)
			{
				$pilotid = trim($matches[2]);
			}
			else
			{
				# Invalid Pilot
				echo '#Answer# Error - Invalid pilot ID format;';
				return;
			}
		}
		
		$pilotdata = PilotData::GetPilotData($pilotid);
		if(!$pilotdata)
		{
			echo '#Answer# Error - Username don\'t exist or wrong password;';
			return;
		}
		
		echo "#Answer# Ok - connected;";
		echo 'Weight='.Config::Get('WeightUnit').' Dist='.Config::Get('DistanceUnit').' Speed='.Config::Get('SpeedUnit').' Alt='.Config::Get('AltUnit').' Liqu='.Config::Get('LiquidUnit');
		echo '#welcome#'.Config::Get('WelcomeMessage').'#endwelcome#';
	}