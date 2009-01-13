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
 * @package module_admin_sitecms
 */
 
class Reports extends CodonModule
{
	function HTMLHead()
	{
		switch($this->get->page)
		{
			case '':
			case 'reports':
					
				Template::Set('sidebar', 'sidebar_reports.tpl');
				
				break;
				
		}
	}
	
	
	function Controller()
	{
		switch($this->get->page)
		{
			case '':
			case 'reports':
			
				Template::Set('acstats', StatsData::AircraftUsage());
				Template::Set('toproutes', StatsData::TopRoutes());
				Template::Show('reports_main.tpl');
				
				break;
				
			case 'financials':
				
				echo '<h3>Coming soon!</h3>';
				
				break;
			
			case 'aircraft':
				
				echo '<h3>Coming soon!</h3>';
				
				$stats = AircraftStats::getAircraftDistances();
				
				DB::debug();
				
				break;
		}
	}
}