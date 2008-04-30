<?php


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