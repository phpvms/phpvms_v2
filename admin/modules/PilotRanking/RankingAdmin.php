<?php


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
		
		if($minhours == '' || $rank == '')
		{
			Template::Set('message', 'Hours and Rank must be blank');
			Template::Show('core_message.tpl');
			return;
		}
		
		if(!is_numeric($minhours))
		{
			Template::Set('message', 'The hours must be a number');
			Template::Show('core_message.tpl');
		}
		
		if(!RanksData::AddRank($minhours, $rank))
		{
			Template::Set('message', DB::error());
			Template::Show('core_message.tpl');
			return;
		}
		else
		{
			Template::Set('message', 'Rank Added!');
			Template::Show('core_message.tpl');
			return;
		}
		
	}
}

?>