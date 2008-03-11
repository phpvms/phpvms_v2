<?php


class Schedules extends ModuleBase
{
	function Controller()
	{
		switch(Vars::GET('page'))
		{
			case 'schedules':
				$depapts = SchedulesData::GetDepartureAirports();
				$depairports = '';
				
				foreach($depapts as $airport)
				{
					$depairports .= '<option value="'.$airport->icao.'">'.$airport->name.'</option>';
				}
				
				Template::Set('depairports', $depairports);
				Template::Show('schedule_searchform.tpl');
				
				Template::Show('schedule_list.tpl');
				
				break;
		}	
	}
}

?>