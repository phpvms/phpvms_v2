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