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
	* To include additional fields,  uncomment the following lines
	*  	set $fieldname to the desired field name (all caps, and spaces
	*	replaced by an underscore), and use the result in $value.
	*/

	//$fieldname = "VATSIM_ID";
	//$value = "";
	//$fields = PilotData::GetFieldData($pilot->pilotid);
	//foreach($fields as $f) {
	//	if ($f->fieldname==$fieldname) {
	//		$value = $f->value;
	//	}
	//}
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