<h3>Aircraft Reports</h3>
<?php
if(!is_array($acstats))
	$acstats = array();
?>
<?php
foreach($acstats as $stat)
{
?>
<h3><?php echo "{$stat->fullname} ({$stat->registration})"?></h3>
<table width="100px">
<tr>
	<td align="right"><strong>Total Flights:</strong></td>
	<td align="left"><?php echo $stat->routesflown==''?0:$stat->routesflown?><br /></td>
</tr>
<tr>
	<td align="right"><strong>Total Distance:</strong></td>
	<td align="left"><?php echo $stat->distance==''?0:$stat->distance?></td>
</tr>
<tr>
	<td align="right" nowrap=""><strong>Average Flight Distance:</strong></td>
	<td align="left"><?php echo $stat->averagedistance==''?0:$stat->averagedistance?></td>
</tr>
<tr>
	<td align="right"><strong>Total Hours:</strong></td>
	<td align="left"><?php echo $stat->totaltime==''?0:$stat->totaltime?></td>
</tr>
<tr>
	<td align="right"><strong>Average Flight Time:</strong></td>
	<td align="left"><?php echo $stat->averagetime==''?0:$stat->averagetime?></td>
</tr>
</table>
</p>
<?php	
}	
?>