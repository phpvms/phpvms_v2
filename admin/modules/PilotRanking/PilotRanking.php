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
	public function HTMLHead()
	{
		if($this->get->page == 'pilotranks'
			|| $this->get->page == 'calculateranks')
		{
			Template::Set('sidebar', 'sidebar_ranks.tpl');
		}
		elseif($this->get->page == 'awards')
		{
			Template::Set('sidebar', 'sidebar_awards.tpl');
		}
	}
	
	public function index()
	{
		$this->pilotranks();
	}
	
	public function pilotranks()
	{
		switch($this->post->action)
		{
			case 'addrank':
				$this->add_rank_post();
				break;
			case 'editrank':
				$this->edit_rank_post();
				break;
			
			case 'deleterank':
				
				$ret = RanksData::DeleteRank($this->post->id);
				
				Template::Set('message', 'Rank deleted!');
				Template::Show('core_success.tpl');
				break;
			
			case 'addaward':
				$this->add_award_post();
				break;				
			case 'editaward':
				$this->edit_award_post();
				break;				
			case 'deleteaward':
				$ret = AwardsData::DeleteAward($this->post->id);
				Template::Set('message', 'Award deleted!');
				Template::Show('core_success.tpl');
				break;
		}
		
		Template::Set('ranks', RanksData::GetAllRanks());
		Template::Show('ranks_allranks.tpl');
	}
	
	public function addrank()
	{
		Template::Set('title', 'Add Rank');
		Template::Set('action', 'addrank');
		
		Template::Show('ranks_rankform.tpl');
	}
	
	public function editrank()
	{
		Template::Set('title', 'Edit Rank');
		Template::Set('action', 'editrank');
		Template::Set('rank', RanksData::GetRankInfo($this->get->rankid));
		
		Template::Show('ranks_rankform.tpl');
	}
	
	public function awards()
	{
		Template::Set('awards', AwardsData::GetAllAwards());
		Template::Show('awards_allawards.tpl');
	}
	
	public function addaward()
	{
		
		Template::Set('title', 'Add Award');
		Template::Set('action', 'addaward');
		
		Template::Show('awards_awardform.tpl');
		
	}
	
	public function editaward()
	{
		Template::Set('title', 'Edit Award');
		Template::Set('action', 'editaward');
		Template::Set('award', AwardsData::GetAwardDetail($this->get->awardid));
		
		Template::Show('awards_awardform.tpl');
		
	}
	
	/* Utility functions */
	
	protected function add_rank_post()
	{
		
		if($this->post->minhours == '' || $this->post->rank == '')
		{
			Template::Set('message', 'Hours and Rank must be blank');
			Template::Show('core_error.tpl');
			return;
		}
		
		if(!is_numeric($this->post->minhours))
		{
			Template::Set('message', 'The hours must be a number');
			Template::Show('core_error.tpl');
			return;
		}
		
		$ret = RanksData::AddRank($this->post->rank, $this->post->minhours, $this->post->imageurl, $this->post->payrate);
	
		if(DB::errno() != 0)
		{
			Template::Set('message', 'Error adding the rank: '. DB::error());
			Template::Show('core_error.tpl');
			return;
		}
		
		Template::Set('message', 'Rank Added!');
		Template::Show('core_success.tpl');
	}
	
	protected function edit_rank_post()
	{
		if($this->post->minhours == '' || $this->post->rank == '')
		{
			Template::Set('message', 'Hours and Rank must be blank');
			Template::Show('core_error.tpl');
			return;
		}
		
		if(!is_numeric($this->post->minhours))
		{
			Template::Set('message', 'The hours must be a number');
			Template::Show('core_error.tpl');
			return;
		}
		
		$ret = RanksData::UpdateRank($this->post->rankid, $this->post->rank, 
								$this->post->minhours, $this->post->rankimage, $this->post->payrate);
		
		if(DB::errno() != 0)
		{
			Template::Set('message', 'Error updating the rank: '.DB::error());
			Template::Show('core_error.tpl');
			return;
		}
		
		Template::Set('message', 'Rank Added!');
		Template::Show('core_success.tpl');
	}
	
	protected function add_award_post()
	{
		if($this->post->name == '' || $this->post->image == '')
		{
			Template::Set('message', 'The name and image must be entered');
			Template::Show('core_error.tpl');
			return;
		}
		
		$ret = AwardsData::AddAward($this->post->name, $this->post->descrip, $this->post->image);
		
		Template::Set('message', 'Award Added!');
		Template::Show('core_success.tpl');
	}
	
	protected function edit_award_post()
	{		
		if($this->post->name == '' || $this->post->image == '')
		{
			Template::Set('message', 'The name and image must be entered');
			Template::Show('core_error.tpl');
			return;
		}
		
		$ret = AwardsData::EditAward($this->post->awardid, $this->post->name, $this->post->descrip, $this->post->image);
		
		Template::Set('message', 'Award Added!');
		Template::Show('core_success.tpl');
	}
}

?>