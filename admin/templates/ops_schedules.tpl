<h3><?php echo $title?></h3>
<div>
<form action="<?php echo SITE_URL.'/admin/index.php/operations/schedules';?>" method="get">
<strong>Filter schedules: </strong><input type="text" name="query" value="<?php if($_GET['query']) { echo $_GET['query'];} else { echo '(Use % for wildcard)';}?>" onClick="this.value='';" />
<select name="type">
	<option value="code">code</option>
	<option value="flightnum">flight number</option>
	<option value="depapt">departure airport</option>
	<option value="arrapt">arrival airport</option>
	<option value="aircraft">aircraft type</option>
</select>

&nbsp;&nbsp;Type
<select name="enabled">
	<option value="all">active+inactive</option>
	<option value="1">only active</option>
	<option value="0">only inactive</option>
</select>
<input type="hidden" name="action" value="filter" />
<input type="submit" name="submit" value="filter" />
</form>
</div>
<?php
if(!$schedules && !isset($paginate))
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
	<th>Route</th>
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
	<td align="center">
		<?php
		if(!empty($sched->route))
		{ ?>
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/operations/viewmap?type=schedule&id=<?php echo $sched->id;?>">View Route</a>
		<?php 
		}
		else
		{
			echo '-';
		}
		?>
	</td>
	<td align="center" width="1%" nowrap>
		<a href="<?php echo SITE_URL?>/admin/index.php/operations/editschedule?id=<?php echo $sched->id;?>">
		<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit Schedule" /></a>
		
		<a href="<?php echo SITE_URL?>/admin/action.php/operations/schedules" action="deleteschedule" 
			id="<?php echo $sched->id;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" />
		</a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<?php
if(isset($paginate))
{
?>
<div style="float: right;">
	<a href="<?php echo SITE_URL.'/admin/index.php/operations/schedules';?>?start=<?php echo $prev?>">Prev Page</a> | 
	<a href="<?php echo SITE_URL.'/admin/index.php/operations/schedules';?>?start=<?php echo $start?>">Next Page</a>
	<br />
</div>
<?php
}
?>