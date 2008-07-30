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
 
class PIREPS extends CodonModule
{
	public $pirep;
	
	function Controller()
	{
		switch($this->get->page)
		{
			case '':
			case 'mine':
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
			
			case 'view':
			case 'viewreport':
			
				$pirepid = $this->get->id;
				$pirep = PIREPData::GetReportDetails($pirepid);
				
				if(!$pirep)
				{
					echo '<p>This PIREP does not exist!</p>';
					return;
				}

				Template::Set('pirep', $pirep);
				Template::Set('comments', PIREPData::GetComments($pirepid));
												
				Template::Show('pirep_viewreport.tpl');
				Template::Show('pirep_map.tpl');
				break;
			
			/* Show map with all of their routes
			*/
			case 'routesmap':
			
				echo '<h3>All flights</h3>';
				$pireps = PIREPData::GetAllReportsForPilot(Auth::$userinfo->pilotid);
				
				if(!$pireps)
				{
					echo '<p>There are no pilot reports</p>';
					return;
				}
				
				$map = new GoogleMap;
				
				foreach($pireps as $pirep)
				{
					$map->AddPoint($pirep->deplat, $pirep->deplong, "$pirep->depname ($pirep->depicao)");
					$map->AddPoint($pirep->arrlat, $pirep->arrlong, "$pirep->arrname ($pirep->arricao)");
					$map->AddPolylineFromTo($pirep->deplat, $pirep->deplong, $pirep->arrlat, $pirep->arrlong);
				}
				
				$map->ShowMap();
				
				break;
				
			case 'filepirep':
				
				Template::Set('pilot', Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname);
				Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
				Template::Set('allairports', OperationsData::GetAllAirports());
				Template::Set('allairlines', OperationsData::GetAllAirlines());
				Template::Set('allaircraft', OperationsData::GetAllAircraft());
				
				Template::Show('pirep_new.tpl');
				break;
				
			case 'getdeptapts':
				
				$code = $this->get->id;
				
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
				$code = $this->get->id;
				$icao = $this->get->icao;
				
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
		
		
		// Load PIREP into RSS feed
		$reports = PIREPData::GetRecentReportsByCount(10);
		$rss = new RSSFeed('Latest Pilot Reports', SITE_URL, 'The latest pilot reports');
		
		foreach($reports as $report)
		{
			$rss->AddItem('Report #'.$report->pirepid.' - '.$report->depicao.' to '.$report->arricao,
							SITE_URL.'/admin/index.php?admin=viewpending','',
							'Filed by '.PilotData::GetPilotCode($report->code, $report->pilotid) . " ($report->firstname $report->lastname)");
		}
		
		$rss->BuildFeed(LIB_PATH.'/rss/latestpireps.rss');
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