<h3><?php echo $title?></h3>
<?php
if(!$schedules)
{
	echo '<p id="error">No schedules exist</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Departure</th>
	<th>Arrival</th>
	<th>Days</th>
	<th>Aircraft</th>
	<th>Distance</th>
	<th>Times Flown</th>
	<th>Details</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($schedules as $sched)
{
?>
<tr id="row<?php echo $sched->id?>" class="<?php echo ($sched->enabled==0)?'disabled':''?>">
	<td align="left"><?php echo $sched->code . $sched->flightnum; ?></td>
	<td align="left"><?php echo $sched->depicao; ?> (<?php echo $sched->deptime;?>)</td>
	<td align="left"><?php echo $sched->arricao; ?> (<?php echo $sched->arrtime;?>)</td>
	<td align="left"><?php echo Util::GetDaysCompact($sched->daysofweek)?></td>
	<td align="left"><?php echo $sched->aircraft.' ('.$sched->registration.')'; ?></td>
	<td align="center"><?php echo $sched->distance; ?></td>
	<td align="center"><?php echo $sched->timesflown; ?></td>
	<td align="center">
		<?php echo $sched->flighttype . ' ('.$sched->maxload.'/'.$sched->price.')'; ?>
	</td>
	<td align="center" width="1%" nowrap>
		<a href="<?php echo SITE_URL?>/admin/index.php/operations/editschedule?id=<?php echo $sched->id;?>">
		<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit Schedule" /></a>
	<?php
	/*
	  <a href="action.php?admin=<?php echo Vars::GET('admin'); ?>"
			class="deleteitem" action="deleteschedule"
			id="<?php echo $sched->id;?>"><img src="lib/images/delete.gif" alt="Delete" /></a>
	 */ ?>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>