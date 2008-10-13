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
	/* 
		To include a custom field, use the following example:

		<td>
			<?php echo PilotData::GetFieldValue($pilot->pilotid, 'VATSIM ID'); ?>
		</td>

		For instance, if you added a field called "IVAO Callsign":

			echo PilotData::GetFieldValue($pilot->pilotid, 'IVAO Callsign');		
	 */
?>
<tr>
	<td><a href="<?=SITE_URL?>/index.php/Pilots/reports/<?=$pilot->pilotid?>">
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