<?php

include dirname(__FILE__) . '/PilotData.class.php';

class PilotAdmin
{
	function NavBar()
	{
		echo '<li><a href="#">Pilots</a>
				<ul>
					<li><a href="?admin=viewpilots">View Registered Pilots</a></li>
				</ul>
				</li>';
	}
	
	function Controller()
	{
		switch(Vars::GET('admin'))
		{
			case 'viewpilots':
			
				if(Vars::GET('action') == 'viewoptions')
				{
					$this->ViewPilotDetails();	
					return;
				}
				$this->ShowPilotsList();	
			break;	
		}
		
	}
	
	function ShowPilotsList()
	{
		$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 
						 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 
						 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		
		Template::Set('allletters', $letters);
		Template::Set('allpilots', PilotData::GetAllPilots(Vars::GET('letter')));
		
		Template::Show('pilots_detailtabs.tpl');
	}
	
	function ViewPilotDetails()
	{
		Template::Set('pilotinfo', PilotData::GetPilotData(Vars::GET('id')));
		
		Template::Show('pilots_details.tpl');
	}
}

?>