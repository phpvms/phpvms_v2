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
 * @package module_admin_pilotranks
 */
 
class PilotRanking
{
	function Controller()
	{
		switch(Vars::POST('action'))
		{
			case 'addrank':
				$this->AddRank();
				break;
			case 'editrank':
				$this->EditRank();
				break;
		}
		
		switch(Vars::GET('admin'))
		{
			case 'addrank':
				Template::Set('title', 'Add Rank');
				Template::Set('action', 'addrank');
				
				Template::Show('ranks_rankform.tpl');
				break;
				
			case 'editrank':
				Template::Set('title', 'Edit Rank');
				Template::Set('action', 'editrank');
				Template::Set('rank', RanksData::GetRankInfo(Vars::GET('rankid')));
				
				Template::Show('ranks_rankform.tpl');
				break;

			case 'calculateranks':
				RanksData::CalculatePilotRanks();
				
			case 'pilotranks':
				
				Template::Set('ranks', RanksData::GetAllRanks());
				Template::ShowTemplate('ranks_allranks.tpl');
				
				break;
		}
	}
	
	function AddRank()
	{
		$minhours = Vars::POST('minhours');
		$rank = Vars::POST('rank');
		$imageurl = Vars::POST('imageurl');
		
		if($minhours == '' || $rank == '')
		{
			Template::Set('message', 'Hours and Rank must be blank');
			Template::Show('core_error.tpl');
			return;
		}
		
		if(!is_numeric($minhours))
		{
			Template::Set('message', 'The hours must be a number');
			Template::Show('core_error.tpl');
		}
		
		if(!RanksData::AddRank($minhours, $rank, $imageurl))
		{
			if(DB::errno() != 0)
			{
				Template::Set('message', 'Error adding the rank: '. DB::error());
				Template::Show('core_error.tpl');
				return;
			}
		}
		DB::debug();
		Template::Set('message', 'Rank Added!');
		Template::Show('core_success.tpl');		
	}
	
	function EditRank()
	{
		$rankid = Vars::POST('rankid');
		$minhours = Vars::POST('minhours');
		$rank = Vars::POST('rank');
		$imageurl = Vars::POST('rankimage');
		
		if($minhours == '' || $rank == '')
		{
			Template::Set('message', 'Hours and Rank must be blank');
			Template::Show('core_error.tpl');
			return;
		}
		
		if(!is_numeric($minhours))
		{
			Template::Set('message', 'The hours must be a number');
			Template::Show('core_error.tpl');
		}
		
		if(!RanksData::UpdateRank($rankid, $rank, $minhours, $imageurl))
		{
			if(DB::errno() != 0)
			{
				Template::Set('message', 'Error updating the rank: '.DB::error());
				Template::Show('core_error.tpl');
				return;
			}
		}
		
		Template::Set('message', 'Rank Added!');
		Template::Show('core_success.tpl');		
	}
}

?>