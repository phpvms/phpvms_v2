<h3><?=$title?></h3>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Pilot ID</th>
	<th>Name</th>	
	<th>Rank</th>
	<th>Flights</th>
	<th>Hours</th>
</tr>
</thead>
<tbody>
<?php
foreach($allpilots as $pilot)
{
?>
<tr>
	<td><a href="?page=pilotreports&pilotid=<?=$pilot->pilotid?>">
			<?=PilotData::GetPilotCode($pilot->code, $pilot->pilotid)?></a>
	</td>
	<td><?=$pilot->firstname.' '.$pilot->lastname?></td>
	<td><img src="<?=$pilot->rankimage?>" alt="<?=$pilot->rank;?>" /></td>
	<td><?=$pilot->totalflights?></td>
	<td><?=$pilot->totalhours?></td>
</tr>
<?php
}
?>
</tbody>
</table>