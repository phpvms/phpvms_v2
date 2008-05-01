<?php


class Pilots
{

	function Controller()
	{
		
		switch(Vars::GET('page'))
		{
			case 'pilots':
			
				Template::Set('allpilots', PilotData::GetAllPilotsDetailed());
				
				Template::Show('pilots_list.tpl');
				break;
				
			case 'pilotreports':
			
				$id = Vars::GET('pilotid');
				
				Template::Set('pireps', PIREPData::GetAllReportsForPilot($id));
				Template::Show('pireps_viewall.tpl');
				break;
		}
	}
}