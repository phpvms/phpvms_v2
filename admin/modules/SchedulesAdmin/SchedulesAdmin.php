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
			
				/* This grabs our airport info via JSON, since remote xhttp requests are
					forbidden through javascript
				 */
				/*if(Vars::GET('action') == 'getapinfo')
				{
					$icao = Vars::GET('icao');
					
					echo file_get_contents('http://ws.geonames.org/searchJSON?style=short&type=json&q='.$icao);
					return;	
				}*/
				
				/* Go on
				 */
				 
				if(Vars::POST('action') == 'addairport')
				{
					$icao = Vars::POST('icao');	
					$name = Vars::POST('name');
					$country = Vars::POST('country');
					$lat = Vars::POST('lat');
					$long = Vars::POST('long');
					
					if($icao == '' || $name == '' || $country == '' || $lat == '' || $long == '')
					{
						Template::Set('message', 'Some fields were blank!');
					}
					else
					{
						if(!SchedulesData::AddAirport($icao, $name, $country, $lat, $long))
							Template::Set('message', 'There was an error adding the airport');
						else	
							Template::Set('message', 'The airport has been added');
					}
						
					Template::Show('core_message.tpl');
				}
				 
				Template::Set('airports', SchedulesData::GetAllAirports());
				Template::Show('ops_airportlist.tpl');
				
				Template::Show('ops_addairport.tpl');
				break;
		}
	}
}
?>