<?php


class SchedulesAdmin
{
	function NavBar()
	{
		echo '<li><a href="#">Operations</a>
				<ul>
					<li><a href="?admin=airports">Airports</a></li>
					<li><a href="?admin=schedules">Flight Schedules</a></li>
				</ul>
				</li>';
	}
	
	function Controller()
	{
		switch(Vars::GET('admin'))
		{
			case 'airports':
			
				Template::Show('ops_addairport.tpl');
				break;
		}
	}
}
?>