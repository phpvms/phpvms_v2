<div class="mapcenter" align="center">
<?php

	$map = new GoogleMapAPI('acarsmap', 'phpVMS');

	$map->setHeight(Config::Get('MAP_HEIGHT'));
	$map->setWidth(Config::Get('MAP_WIDTH'));
	$map->setMapType(Config::Get('MAP_TYPE'));
	
	$i=0;
	foreach($acarsdata as $data)
	{
		// Use red for on ground, green for in air
		
		if($data->phasedetail != 'Boarding' && $data->phasedetail != 'Taxiing'
			&& $data->phasedetail != 'FSACARS Closed' && $data->phasedetail != 'Taxiiing to gate'
			&& $data->phasedetail != 'Landed' && $data->phasedetail != 'Arrived')
		{
			$map->addMarkerIcon(SITE_URL.'/lib/images/inair.png');
		}
		else
		{
			$map->addMarkerIcon(SITE_URL.'/lib/images/onground.png');
		}
		
		$map->addMarkerByCoords($data->lng, $data->lat, '', "$data->pilotid (Route $data->flightnum)<br />$data->depapt ($data->depicao) to $data->arrapt ($data->arricao)");
	
		//<td><a href=\"".SITE_URL."/index.php/profile/view/$data->pilotid\">$data->pilotid</a></td>	
		
		$row .= "<tr>
					<td><a href=\"javascript:myclick($i)\">$data->pilotid</a></td>	
					<td>$data->flightnum</td>
					<td>$data->depapt ($data->depicao)</td>
					<td>$data->arrapt ($data->arricao)</td>
					<td>$data->phasedetail</td>
					<td>$data->alt</td>
					<td>$data->gs</td>
					<td>$data->distremain ($data->timeremaining min)</td>
				</tr>";
		
		$i++;
	}

	$map->setAPIKey(GOOGLE_KEY);
	$map->printHeaderJS();
	$map->printMapJS();
	
	// Extra map code to handle custom stuff:
	?>
<script type="text/javascript" charset="utf-8">
//<![CDATA[
	
//]]>
</script>
<?php
	
	$map->printMap();
	$map->printOnLoad();
?>
</div>
<div>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Pilot</th>
	<th>Flight Number</th>
	<th>Departure</th>
	<th>Arrival</th>
	<th>Phase</th>
	<th>Altitude</th>
	<th>Speed</th>
	<th>Distance Remain</th>
</tr>
</thead>
<tbody>
<?=$row ?>
</tbody>
</table>
</div>