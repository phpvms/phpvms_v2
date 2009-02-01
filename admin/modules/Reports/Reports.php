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
		Template::Set('sidebar', 'sidebar_reports.tpl');
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
				
			case 'aircraft':
				
				$acstats = AircraftStats::getAircraftDetails();
								
				Template::Set('acstats', $acstats);
				Template::Show('reports_aircraft.tpl');
				
				break;
		}
	}
}