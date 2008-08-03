<?php
if(!$allroutes)
{
	echo '<p align="center">No routes have been found!</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Route</th>
	<th>Aircraft</th>
	<th>Departure</th>
	<th>Arrival</th>
	<th>Distance</th>
	<?php if (Auth::LoggedIn())
	{ ?>
	<th>Options</th>
	<?php
	} ?>
</tr>
</thead>
<tbody>
<?php
foreach($allroutes as $route)
{
	if(Config::Get('SHOW_LEG_TEXT') == true // Want it to show
			&& $route->leg != '' && $route->leg != '0') // And it isn't blank or 0
		$leg = 'Leg '.$route->leg;
	else
		$leg = '';
?>
<tr>
	<td><a href="<?=SITE_URL?>/index.php/schedules/details/<?=$route->id?>"><?=$route->code . $route->flightnum; ?> <?=$leg?></a></td>
	<td align="center"><?=$route->depname?> (<?=$route->depicao; ?>) to <?=$route->arrname?> (<?=$route->arricao; ?>)</td>
	<td align="center"><?=$route->aircraft; ?></td>
	<td><?=$route->deptime;?></td>
	<td><?=$route->arrtime;?></td>
	<td><?=$route->distance;?></td>
	<?php if (Auth::LoggedIn())
	{ ?>
	<td><a id="<?=$route->id; ?>" class="addbid" href="<?=SITE_URL?>/action.php/Schedules/addbid/">Add to Bid</a>
	<?php
	} ?>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>