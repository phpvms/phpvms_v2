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
		$depairports = '';
		
		
		
		Template::Set('depairports', $depapts);
		Template::Show('schedule_searchform.tpl');
		
		Template::Show('schedule_list.tpl');
		
	}
	
	function FindFlight()
	{
		$depicao = Vars::POST('depicao');
		
		if($depicao == '')
			return;
			
		Template::Set('allroutes', SchedulesData::GetRoutesWithDeparture($depicao));
		Template::Show('schedule_results.tpl');
	}
}

?>