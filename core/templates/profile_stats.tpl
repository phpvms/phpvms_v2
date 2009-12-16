<h3>Your Stats</h3>
<?php

$data = StatsData::PilotAircraftFlownGraph($pilot->pilotid , true); 
echo $data;

print_r(StatsData::PilotAircraftFlownCounts($pilot->pilotid));