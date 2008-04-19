<h3>Route Map</h3>
<?php
	$map = new GoogleMap;
	$map->AddPoint($pirep->deplat, $pirep->deplong, "$pirep->depname ($pirep->depicao)");
	$map->AddPoint($pirep->arrlat, $pirep->arrlong, "$pirep->arrname ($pirep->arricao)");
	$map->AddPolylineFromTo($pirep->deplat, $pirep->deplong, $pirep->arrlat, $pirep->arrlong);

	$map->ShowMap('800px', '600px');
?>