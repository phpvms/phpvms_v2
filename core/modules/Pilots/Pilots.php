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
 
class Pilots extends CodonModule
{
	
	public function index()
	{
		// Get all of our hubs, and list pilots by hub
		$allhubs = OperationsData::GetAllHubs();
		
		if(!$allhubs) $allhubs = array();
		
		foreach($allhubs as $hub)
		{
			$this->set('title', $hub->name);
			$this->set('icao', $hub->icao);
			
			$this->set('allpilots', PilotData::GetAllPilotsByHub($hub->icao));
								
			$this->render('pilots_list.tpl');
		}
		
		$nohub = PilotData::GetAllPilotsByHub('');
		if(!$nohub)
		{
			return;
		}
		
		$this->set('title', 'No Hub');
		$this->set('icao', '');
		$this->set('allpilots', $nohub);
		$this->render('pilots_list.tpl');
	}
	
	public function reports($pilotid='')
	{
		if($pilotid == '')
		{
			$this->set('message', 'No pilot specified!');
			$this->render('core_error.tpl');
			return;
		}
		
		$this->set('pireps', PIREPData::GetAllReportsForPilot($pilotid));
		$this->render('pireps_viewall.tpl');
	}
	
	
	/* Stats stuff for charts */
	
	
	public function statsdaysdata($pilotid)
	{
		$data = PIREPData::getIntervalDataByDays(array('p.pilotid'=>$pilotid), 30);
		$this->create_line_graph($data);
	}
	
	public function statsmonthsdata($pilotid)
	{
		$data = PIREPData::getIntervalDataByMonth(array('p.pilotid'=>$pilotid), 3);
		$this->create_line_graph($data);
	}
	
	public function statsaircraftdata($pilotid)
	{
		$data = StatsData::PilotAircraftFlownCounts($pilotid);
		if(!$data) $data = array();
		
		include CORE_LIB_PATH.'/php-ofc-library/open-flash-chart.php';
		
		$title = new title('Aircraft Flown');
		
		$d = array();
		foreach($data as $ac)
		{
			$d[] = new pie_value(floatval($ac->hours), $ac->aircraft);
		}

		$pie = new pie();
		$pie->start_angle(35)
			->add_animation( new pie_fade() )
			->add_animation( new pie_bounce(4) )
			// ->label_colour('#432BAF') // <-- uncomment to see all labels set to blue
			->gradient_fill()
			->tooltip( '#val# of #total#<br>#percent# of 100%' )
			->colours(
				array(
					'#1F8FA1',    // <-- blue
					'#848484',    // <-- grey
					'#CACFBE',    // <-- green
					'#DEF799'    // <-- light green
					) );

		$pie->set_values( $d );

		$chart = new open_flash_chart();
		$chart->set_title( $title );
		$chart->add_element( $pie );
		$chart->set_bg_colour( '#FFFFFF' );

		echo $chart->toPrettyString();
	}
	
	protected function create_line_graph($data)
	{	
		if(!$data)
		{
			$data = array();
		}
				
		$bar_values = array();
		$bar_titles = array();
		foreach($data as $val)
		{
			$bar_titles[] = $val->ym;
			$bar_values[] = floatval($val->total);
		}
	
		include CORE_LIB_PATH.'/php-ofc-library/open-flash-chart.php';

		$title = new title( 'Past 30 days PIREPs' );

		// ------- LINE 2 -----
		$d = new solid_dot();
		$d->size(3)->halo_size(1)->colour('#3D5C56');

		$line = new line();
		$line->set_default_dot_style($d);
		$line->set_values( $bar_values );
		$line->set_width( 2 );
		$line->set_colour( '#3D5C56' );
		
		$x_labels = new x_axis_labels();
		$x_labels->set_labels( $bar_titles );

		$x = new x_axis();
		$x->set_labels( $x_labels );
		
		$chart = new open_flash_chart();
		$chart->set_title( $title );
		$chart->add_element( $line );
		$chart->set_y_axis( $y );
		$chart->set_x_axis( $x );
		$chart->set_bg_colour( '#FFFFFF' );

		echo $chart->toPrettyString();
	}
		
	public function RecentFrontPage($count = 5)
	{
		$this->set('pilots', PilotData::GetLatestPilots($count));
		$this->render('frontpage_recentpilots.tpl');
	}
}