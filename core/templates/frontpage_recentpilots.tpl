<?php

foreach($pilots as $pilot)
{
?>
<p><a href="<?php echo SITE_URL?>/index.php/profile/view/<?php echo $pilot->pilotid?>"><?php echo PilotData::GetPilotCode($pilot->code, $pilot->pilotid). ' ' .$pilot->firstname . ' ' . $pilot->lastname?></a></p>
<?php
}
?>