<h3>Route Map</h3>
<?php
	$map = new GoogleMap;
	$map->AddPoint($pirep->deplat, $pirep->deplong, "$pirep->depname ($pirep->depicao)");
	$map->AddPoint($pirep->arrlat, $pirep->arrlong, "$pirep->arrname ($pirep->arricao)");
	$map->AddPolylineFromTo($pirep->deplat, $pirep->deplong, $pirep->arrlat, $pirep->arrlong);

	// Center the map
	$centerlat = ($pirep->deplat + $pirep->arrlat) / 2;
	$centerlong = ($pirep->deplong + $pirep->arrlong) / 2;
	$map->CenterMap($centerlat, $centerlong);
	
	$map->ShowMap(MAP_WIDTH, MAP_HEIGHT);
?>