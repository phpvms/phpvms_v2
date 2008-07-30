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
 
class PilotRanking extends CodonModule
{

	function HTMLHead()
	{
	
		if($this->get->admin == 'pilotranks'
			|| $this->get->admin == 'calculateranks')
		{
			Template::Set('sidebar', 'sidebar_ranks.tpl');
		}
	}
	function Controller()
	{
		switch($this->post->action)
		{
			case 'addrank':
				$this->AddRank();
				break;
			case 'editrank':
				$this->EditRank();
				break;
		}
		
		switch($this->get->admin)
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
				// no break, show the ranks again
			case 'pilotranks':
				
				Template::Set('ranks', RanksData::GetAllRanks());
				Template::ShowTemplate('ranks_allranks.tpl');
				
				break;
		}
	}
	
	function AddRank()
	{
		$minhours = $this->post->minhours;
		$rank = $this->post->rank;
		$imageurl = $this->post->imageurl;
		
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
		$rankid = $this->post->rankid;
		$minhours = $this->post->minhours;
		$rank = $this->post->rank;
		$imageurl = $this->post->rankimage;
		
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