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
 
class StatsData
{
	/**
	 * Show pie chart for all of the aircraft flown
	 *  by a certain pilot. Outputs image, unless $ret == true,
	 * 	then it returns the URL.
	 */
	function PilotAircraftFlownGraph($pilotid, $ret = false)
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

		if($ret == true)
			return $chart->draw(false);
		else
			echo '<img src="'.$chart->draw(false).'" align="center" />';
	}
}
?>