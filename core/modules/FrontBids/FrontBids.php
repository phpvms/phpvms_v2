<?php

class FrontBids extends CodonModule
{

	public function Controller()
	{
		$this->RecentFrontPage();
	}
	
    public function RecentFrontPage()
	{

		Template::Set('lastbids', SchedulesData::GetLatestBids());
		Template::Show('frontpage_recentbids.tpl');
	}
}		

?>
