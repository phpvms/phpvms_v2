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
 * @package module_admin_dashboard
 */
 
/**
 * This file handles any misc tasks that need to be done.
 * Loaded at the very end
 */

class Dashboard
{

	function Controller()
	{
		/*
		 * Check for updates
		 */
		switch($_GET['admin'])
		{
			case '':

				/* Dashboard.tpl calls the functions below
				*/
				Template::Show('dashboard.tpl');

                /*Template::Set('allpilots', PilotData::GetPendingPilots());
				Template::Show('pilots_pending.tpl');*/
				break;

			case 'about':

				Template::Show('core_about.tpl');

				break;
		}
	}

	/**
	 * Show the notification that an update is available
	 */
	function CheckForUpdates()
	{
		if(NOTIFY_UPDATE == true)
		{
			$postversion = @file_get_contents('http://www.phpvms.net/version.php');

			if(trim($postversion) != PHPVMS_VERSION && $postversion !== false)
			{
				Template::Set('message', 'An update for phpVMS is available!');
				Template::Show('core_error.tpl');
			}
		}
	}
}
?>