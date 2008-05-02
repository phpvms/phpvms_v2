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
 * @package core_api
 */
 
class StatsData
{
	/**
	 * Show pie chart for all of the aircraft flown
	 *  by a certain pilot
	 */
	function PilotAircraftFlownGraph($pilotid)
	{
		//Select aircraft types
		$sql = 'SELECT aircraft, COUNT(aircraft) count
					FROM '.TABLE_PREFIX.'pireps
					WHERE pilotid='.$pilotid.'
					GROUP BY aircraft';

		$stats = DB::get_results($sql);

		if(!$stats)
		{
			return;
		}
		
		$data = '';
		$labels = '';
		foreach($stats as $stat)
		{
			if($stat->aircraft == '') continue;

			$data .= $stat->count . ',';
			$labels .= $stat->aircraft.'|';
		}

		// remove that final lone char
		$data = substr($data, 0, strlen($data)-1);
		$labels = substr($labels, 0, strlen($labels)-1);

		$chart = new googleChart($data, 'pie');
		$chart->dimensions = '350x200';
		$chart->setLabels($labels);

		echo '<img src="'.$chart->draw(false).'" align="center" />';
		//Template::Set('ac_chart_url', $chart->draw(false));
	}

	/**
	 * Show the graph of the past week's reports
	 */
	function ShowReportCounts()
	{
		// Recent PIREP #'s
		$max = 0;
		$data = array();

		// This is for the past 7 days
		for($i=-7;$i<=0;$i++)
		{
			$date = mktime(0,0,0,date('m'), date('d') + $i ,date('Y'));
			$count = PIREPData::GetReportCount($date);

			array_push($data, intval($count));
			$label .= date('m/d', $date) .'|';

			if($count > $max)
				$max = $count;
		}

		$chart = new googleChart($data);
		$chart->dimensions = '700x200';
		$chart->setLabelsMinMax($max,'left');
		$chart->setLabels($label,'bottom');

		echo '<img src="'.$chart->draw(false).'" align="center" />';
	}
}
?>