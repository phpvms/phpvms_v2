<?php

class PIREPS extends ModuleBase
{
	function Controller()
	{
		
		switch(Vars::GET('page'))
		{
		
			case 'viewpireps':
				
				if(isset($_POST['submit_pirep']))
				{
					if(!$this->SubmitPIREP())
					{
						Template::Show('pirep_new.tpl');
						return;
					}
				}
				
				// Show PIREPs filed
				
				Template::Set('accepted', PIREPData::GetReportsByAcceptStatus(Auth::$userinfo->pilotid, 1));
				Template::Set('pending', PIREPData::GetReportsByAcceptStatus(Auth::$userinfo->pilotid, 0));
				
				
				Template::Show('pireps_viewall.tpl');
				
				break;
			
			case 'viewreport':
			
				Template::Set('report', PIREPData::GetReportInfo(Vars::GET('id')));
				
				Template::Show('pirep_viewreport.tpl');
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
				
				echo '<select id="depicao" name="depicao">
						<option value="">Select a Departure Airport';
				foreach($allapts as $airport)
				{
					echo '<option value="'.$airport->icao.'">'.$airport->icao . ' - '.$airport->name .'</option>';
				}
				echo '</select>';
				
				break;
				
			case 'getarrapts':
				$icao = Vars::GET('icao');
				$code = Vars::GET('code');
				
				if($icao == '') return;
				
				$allapts = SchedulesData::GetArrivalAiports($icao, $code); 
				
				echo '<select name="arricao">
						<option value="">Select an Arrival Airport';
				foreach($allapts as $airport)
				{
					echo '<option value="'.$airport->icao.'">'.$airport->icao . ' - '.$airport->name .'</option>';
				}
				echo '</select>';
				
				break;
		}
	}
	
	function SubmitPIREP()
	{
		$pilotid = Auth::$userinfo->pilotid;
		$code = Vars::POST('code');	
		$flightnum = Vars::POST('flightnum');
		$depicao = Vars::POST('depicao');
		$arricao = Vars::POST('arricao');
		$flighttime = Vars::POST('flighttime');
		$comment = Vars::POST('comment');
				
		if($code == '' || $flightnum == '' || $depicao == '' || $arricao == '' || $flighttime == '')
		{
			Template::Set('message', 'You must fill out all of the required fields!');
			return false;
		}
		
		if(!PIREPData::FileReport($pilotid, $code, $flightnum, $depicao, $arricao, $flighttime, $comment))
		{
			Template::Set('message', 'There was an error adding your PIREP');
			return false;
		}
		
		return true;	
	}
}
		
?>