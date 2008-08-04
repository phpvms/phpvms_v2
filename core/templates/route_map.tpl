<h3>Route Map</h3>
<?php

	// I do this because they can both contain the coordinates
	if($pirep)
		$mapdata = $pirep;
	if($schedule)
		$mapdata = $schedule;
		
	$map = new GoogleMap;
	$map->maptype = Config::Get('MAP_TYPE');
	$map->linecolor = Config::Get('MAP_LINE_COLOR');
	$map->AddPoint($mapdata->deplat, $mapdata->deplong, "$mapdata->depname ($mapdata->depicao)");
	$map->AddPoint($mapdata->arrlat, $mapdata->arrlong, "$mapdata->arrname ($mapdata->arricao)");
	$map->AddPolylineFromTo($mapdata->deplat, $mapdata->deplong, $mapdata->arrlat, $mapdata->arrlong);

	// Center the map
	$centerlat = ($mapdata->deplat + $mapdata->arrlat) / 2;
	$centerlong = ($mapdata->deplong + $mapdata->arrlong) / 2;
	$map->CenterMap($centerlat, $centerlong);
	
	$map->ShowMap(MAP_WIDTH, MAP_HEIGHT);
?>
<br />