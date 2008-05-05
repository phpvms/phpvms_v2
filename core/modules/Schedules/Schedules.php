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
 * @package module_schedules
 */
 

class Schedules extends ModuleBase
{
	function Controller()
	{
		switch(Vars::GET('page'))
		{
			case 'schedules':
				
				if(Vars::POST('action') == 'findflight')
				{
					$this->FindFlight();
					return;
				}
				
				$this->ShowSchedules();
				
				break;
		}	
	}
	
	function ShowSchedules()
	{
		$depapts = SchedulesData::GetDepartureAirports();
				
		Template::Set('depairports', $depapts);
		Template::Show('schedule_searchform.tpl');
		
		// Show the routes. Remote this to not show them.
		Template::Set('allroutes', SchedulesData::GetSchedules());
		
		Template::Show('schedule_list.tpl');
	}
	
	function FindFlight()
	{
		$depicao = Vars::POST('depicao');
		
		if($depicao == '')
		{
			Template::Set('allroutes', SchedulesData::GetSchedules());
		}
		else
		{
			Template::Set('allroutes', SchedulesData::GetRoutesWithDeparture($depicao));
		}
		
		Template::Show('schedule_results.tpl');
	}
}

?>