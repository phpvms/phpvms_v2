<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 *  This program is free software; you can redistribute it and/or modify it 
 *  under the terms of the GNU General Public License as published by the Free 
 *	Software Foundation; either version 2 of the License, or (at your option) 
 *	any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT 
 *  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 *	FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with 
 *	this program; if not, write to the:
 *		Free Software Foundation, Inc., 
 *		59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license GNU Public License http://opensource.org/licenses/gpl-license.php
 * @package module_pireps
 */
 
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
		
		
		// Load PIREP into RSS feed
		$reports = PIREPData::GetRecentReportsByCount(10);
		$rss = new RSSFeed('Latest Pilot Reports', SITE_URL, 'The latest pilot reports');
		
		foreach($reports as $report)
		{
			$rss->AddItem('Report #'.$report->id.' - '.$report->depicao.' to '.$report->arricao, 
							SITE_URL.'/admin/index.php?admin=viewpending','', 
							'Filed by '.PilotData::GetPilotCode($report->code, $report->pilotid));;
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