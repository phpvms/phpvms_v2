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

class VACentral extends CodonModule
{
	public function HTMLHead()
	{
		Template::Set('sidebar', 'sidebar_central.tpl');
	}
	
	public function Controller()
	{
		
		switch($this->get->page)
		{
			case '':
			default:
			
				Template::Show('central_main.tpl');
			
				break;
				
			case 'sendqueuedpireps':
				
				$pireps = PIREPData::getReportsByExportStatus(false);
				
				if(!$pireps)
				{
					echo 'You have no PIREPs waiting to be exported!';
					return;
				}
				
				foreach($pireps as $pirep)
				{
					$resp = CentralData::send_pirep($pirep->pirepid);
					if($resp === true)
					{
						echo "Exported PIREP #{$pirep->pirepid}<br />";
					}
					else 
					{
						echo "FAILED exporting PIREP #{$pirep->pirepid} - ".CentralData::$last_error.'<br />';
					}
				}
				
				echo "Completed";
				
				break;
				
			case 'sendschedules':
			
				echo '<h3>Sending schedules...</h3>';
				$ret = CentralData::send_schedules();
				$this->parse_response($ret);
				
				break;
				
			case 'sendpireps':
				
				echo '<h3>Sending all PIREPS</h3>';
				$ret = CentralData::send_all_pireps();
				$this->parse_response($ret);
				
				break;
		}	
	}
	
	protected function parse_response($resp)
	{
		if((int)CentralData::$response->responsecode == 200)
		{
			echo "Successfully sent message! (Server said \"".CentralData::$response->detail."\")";
		}
		else
		{
			echo "There was an error, server said \"".CentralData::$response->detail."\"";
		}		
	}
		
	
}