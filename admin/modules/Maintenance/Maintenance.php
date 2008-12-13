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

class Maintenance extends CodonModule
{
	
	public function HTMLHead()
	{
		if($this->get->admin == 'resetsignatures')
		{
			Template::Set('sidebar', '<h3>Maintanence</h3>From here you can perform site maintenance');		
		}
	}
	
	public function Controller()
	{
		
		if($this->get->admin == 'resetsignatures')
		{			
			$allpilots = PilotData::GetAllPilots();
			
			echo '<h3>Regenerating signatures</h3>Generating signatures<br />';
			
			foreach($allpilots as $pilot)
			{
				echo "Generating signature for $pilot->firstname $pilot->lastname<br />";
				PilotData::GenerateSignature($pilot->pilotid);
			}
			
			echo "Done";
		}
	}
}