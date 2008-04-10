<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2008
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