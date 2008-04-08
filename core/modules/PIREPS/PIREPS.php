<?php

class PIREPS extends ModuleBase
{
	public $pirep;
	
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
							
				Template::Set('pireps', PIREPData::GetAllReportsForPilot(Auth::$userinfo->pilotid));
				
				Template::Show('pireps_viewall.tpl');
				
				break;
			
			case 'viewreport':
			
				$pirepid = Vars::GET('pirepid');
				$this->pirep = PIREPData::GetReportDetails($pirepid);
				
						
				Template::Set('report', $this->pirep);
				Template::Set('points', array(array($this->pirep->deplat, $this->pirep->deplong),
											  array($this->pirep->arrlat, $this->pirep->arrlong)));
				Template::Set('comments', PIREPData::GetComments($pirepid));
				
				Template::Show('pirep_viewreport.tpl');
				break;
				
			case 'filepirep':
				
				Template::Set('pilot', Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname);
				Template::Set('allairlines', OperationsData::GetAllAirlines());
				Template::Set('allaircraft', OperationsData::GetAllAircraft());
				
				Template::Show('pirep_new.tpl');
				break;
				
			case 'getdeptapts':
				
				$code = Vars::GET('code');
				
				if($code=='') return;
				
				$allapts = SchedulesData::GetDepartureAirports($code);
				
				if(!$allapts)
				{
					echo 'There are no routes for this airline<br />';
					return;
				}
				
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
				
				if(!$allapts)
					return;
					
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
		$aircraft = Vars::POST('aircraft');
		$flighttime = Vars::POST('flighttime');
		$comment = Vars::POST('comment');
				
		if($code == '' || $flightnum == '' || $depicao == '' || $arricao == '' || $aircraft == '' || $flighttime == '')
		{
			Template::Set('message', 'You must fill out all of the required fields!');
			return false;
		}
		
		if(!PIREPData::FileReport($pilotid, $code, $flightnum, $depicao, $arricao, $aircraft, $flighttime, $comment))
		{
			Template::Set('message', 'There was an error adding your PIREP');
			return false;
		}
				
		return true;	
	}
	
	/**
	 *
	 */
	function RecentFrontPage($count = 10)
	{		
		Template::Set('reports', PIREPData::GetRecentReportsByCount($count));	
		Template::Show('frontpage_reports.tpl');
	}
}
		
?>