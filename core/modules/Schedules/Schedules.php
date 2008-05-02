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
 * @package module_schedules
 */
 

class Schedules extends ModuleBase
{
	function Controller()
	{
		switch(Vars::GET('page'))
		{
			case 'schedules':
				
				if(Vars::POST('action') == 'findflight')
				{
					$this->FindFlight();
					return;
				}
				
				$this->ShowSchedules();
				
				break;
		}	
	}
	
	function ShowSchedules()
	{
		$depapts = SchedulesData::GetDepartureAirports();
				
		Template::Set('depairports', $depapts);
		Template::Show('schedule_searchform.tpl');
		
		// Show the routes. Remote this to not show them.
		Template::Set('allroutes', SchedulesData::GetSchedules());
		
		Template::Show('schedule_list.tpl');
	}
	
	function FindFlight()
	{
		$depicao = Vars::POST('depicao');
		
		if($depicao == '')
		{
			Template::Set('allroutes', SchedulesData::GetSchedules());
		}
		else
		{
			Template::Set('allroutes', SchedulesData::GetRoutesWithDeparture($depicao));
		}
		
		Template::Show('schedule_results.tpl');
	}
}

?>