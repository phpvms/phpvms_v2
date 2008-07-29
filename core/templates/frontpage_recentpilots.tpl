<?php

foreach($pilots as $pilot)
{
?>
<p><a href="?page=pilotprofile&pilotid=<?=$pilot->pilotid?>"><?=PilotData::GetPilotCode($pilot->code, $pilot->pilotid). ' ' .$pilot->firstname . ' ' . $pilot->lastname?></a></p>
<?php
}
?>