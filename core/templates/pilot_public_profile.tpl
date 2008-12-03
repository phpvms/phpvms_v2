<?php

if(!$userinfo)
{
	echo '<h3>This pilot does not exist!</h3>';
	return;
}
?>

<h3>Profile For <?php echo $userinfo->firstname . ' ' . $userinfo->lastname?></h3>

<p>
<strong>Pilot ID: </strong><?php echo PilotData::GetPilotCode($userinfo->code, $userinfo->pilotid); ?><br />
<strong>Rank: </strong><img src="<?php echo RanksData::GetRankImage($userinfo->rank)?>" alt="<?php echo $userinfo->rank;?>" /> <br />
<strong>Total Flights: </strong><?php echo $userinfo->totalflights?><br />
<strong>Total Hours: </strong><?php echo $userinfo->totalhours?><br />
<strong>Location: </strong><img src="<?php echo Countries::getCountryImage($userinfo->location);?>" 
			alt="<?php echo Countries::getCountryName($userinfo->location);?>" />
			<?php echo Countries::getCountryName($userinfo->location);?> 
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