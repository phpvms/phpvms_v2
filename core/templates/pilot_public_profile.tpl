<?php

if(!$userinfo)
{
	echo '<h3>This pilot does not exist!</h3>';
	return;
}
?>

<h3>Profile For <?=$userinfo->firstname . ' ' . $userinfo->lastname?></h3>

<p>
<strong>Pilot ID: </strong><?=PilotData::GetPilotCode($userinfo->code, $userinfo->pilotid); ?><br />
<strong>Rank: </strong><img src="<?=RanksData::GetRankImage($userinfo->rank)?>" alt="<?=$userinfo->rank;?>" /> <br />
<strong>Total Flights: </strong><?=$userinfo->totalflights?><br />
<strong>Total Hours: </strong><?=$userinfo->totalhours?>
</p>
<p>
<?php
// Show the public fields
foreach($allfields as $field)
{
	echo "<strong>$field->title: </strong>$field->value<br />";
}
?>
</p>