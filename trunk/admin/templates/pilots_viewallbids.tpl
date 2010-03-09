<h3>Bids List</h3>
<p>These are all the bids which currently are open.</p>
<?php /*echo '<pre>'; print_r($allbids); echo '</pre>';*/ 
if(!$allbids)
{
	echo 'There are no bids!';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Route</th>
	<th>Pilot</th>	
	<th>Day Filed</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allbids as $bid)
{?>
	<tr>
	<td><?php echo $bid->code.$bid->flightnum."({$bid->depicao} - {$bid->arricao})"?></td>
	<td><?php echo PilotData::GetPilotCode($bid->code, $bid->flightnum).' - '.$bid->firstname.' '.$bid->lastname; ?></td>
	<td><?php echo $bid->dateadded; ?></td>
	<td><a href="<?php echo SITE_URL?>/admin/action.php/pilotadmin/viewbids" action="deletebid"
			id="<?php echo $bid->bidid;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a>
		</td>
	</tr>
<?php } ?>
</tbody>
</table>