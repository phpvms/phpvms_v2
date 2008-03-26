<?php

class PIREPS extends ModuleBase
{
	function Controller()
	{
		
		switch(Vars::GET('page'))
		{
						
			case 'viewpireps':
				
				break;
			
			
			case 'filepirep':
				
				Template::Set('pilot', Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname);
				Template::Set('allairlines', OperationsData::GetAllAirlines());
				
				Template::Show('pirep_new.tpl');
				break;
				
			case 'getdeptapts':
				
				$code = Vars::GET('code');
				
				if($code=='') return;
				
				$allapts = SchedulesData::GetDepartureAirports($code);
				
				echo '<select name="depicao">
						<option value="">Select a Departure Airport';
				foreach($allapts as $airport)
				{
					echo '<option value="'.$airport->icao.'">'.$airport->icao . ' - '.$airport->name .'</option>';
				}
				echo '</select>';
				
				break;
			
		}
	}
}
		
?>