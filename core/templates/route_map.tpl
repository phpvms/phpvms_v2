<h3>Route Map</h3>
<div class="mapcenter" align="center">
<?php

    # I do this because they can both contain the coordinates
    if($pirep)
        $mapdata = $pirep;
    if($schedule)
        $mapdata = $schedule;
        
    $centerlat = ($mapdata->deplat + $mapdata->arrlat) / 2;
    $centerlong = ($mapdata->deplong + $mapdata->arrlong) / 2;
    
    $map = new GoogleMapAPI('routemap', 'phpVMS');
    $map->sidebar=false;
    
    $map->addMarkerIcon(SITE_URL.'/lib/images/towerdeparture.png', 35, 35); //, '', 0, 0, 10, 10);
    $map->addMarkerByCoords($mapdata->deplong, $mapdata->deplat, '', "$mapdata->depname ($mapdata->depicao)");
    
    $map->addMarkerIcon(SITE_URL.'/lib/images/towerarrival.png', 35, 35); //, 0, 0, 40, 40);
    $map->addMarkerByCoords($mapdata->arrlong, $mapdata->arrlat, '', "$mapdata->arrname ($mapdata->arricao)");
    
    $map->addPolyLineByCoords($mapdata->deplong, $mapdata->deplat, $mapdata->arrlong, $mapdata->arrlat, Config::Get('MAP_LINE_COLOR'), 5, 50);

    $map->adjustCenterCoords($centerlong, $centerlat);
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