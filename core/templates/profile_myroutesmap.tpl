<h3>My Routes Map</h3>
<div class="mapcenter" align="center">
<?php

	# These hold ones which have already been shown
    $airports = array();
	$routes = array();    
    
    $map = new GoogleMapAPI('routemap', 'phpVMS');
    $map->sidebar=false;
    
    foreach($allroutes as $route)
    {
		# Prevent an airport from being down a few times
		if(!in_array($route->depicao, $airports))
		{
			$map->addMarkerIcon(SITE_URL.'/lib/images/towerdeparture.png', 35, 35); //, '', 0, 0, 10, 10);
			$map->addMarkerByCoords($route->deplong, $route->deplat, '', "$route->depname ($route->depicao)");
			$airports[] = $route->depicao;
		}
	    
	    if(!in_array($route->arricao, $airports))
	    {
			$map->addMarkerIcon(SITE_URL.'/lib/images/towerarrival.png', 35, 35); //, 0, 0, 40, 40);
			$map->addMarkerByCoords($route->arrlong, $route->arrlat, '', "$route->arrname ($route->arricao)");
			$airports[] = $route->arricao;
		}
	    
	    if(!in_array($route->code.$route->flightnum, $routes))
	    {
			$map->addPolyLineByCoords($route->deplong, $route->deplat, $route->arrlong, $route->arrlat, 
										Config::Get('MAP_LINE_COLOR'), 5, 50);
			$routes[] = $route->code.$route->flightnum;
		}
		
		$centerlat = ($route->deplat + $route->arrlat);
		$centerlong = ($route->deplong + $route->arrlong);
	}

	$total = count($allroutes);
    $map->adjustCenterCoords($centerlong/$total, $centerlat/$total);
    $map->setHeight(Config::Get('MAP_HEIGHT'));
    $map->setWidth(Config::Get('MAP_WIDTH'));
    $map->setMapType(Config::Get('MAP_TYPE'));
    
    $map->setAPIKey(GOOGLE_KEY);
    $map->printHeaderJS();
    $map->printMapJS();
    
    $map->printMap();
    $map->printOnLoad();
?>
</div>