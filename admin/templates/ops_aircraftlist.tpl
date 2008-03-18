<h3>Aircraft List</h3>
<p>These are all the aircraft that your airline operates.</p>

<p><a id="dialog" class="jqModal" href="action.php?admin=addaircraft">Add an aircraft</a></p>
<br />
<?php
if(!$allaircraft)
{
	echo '<p>There are no airports added</p><br /><br />';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>ICAO</th>
	<th>Name</th>	
	<th>Full Name</th>
	<th>Range</th>
	<th>Weight</th>
	<th>Cruise</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allaircraft as $aircraft)
{
?>
<tr>
	<td align="center"><?=$aircraft->icao; ?></td>
	<td align="center"><?=$aircraft->name; ?></td>
	<td align="center"><?=$aircraft->fullname; ?></td>
	<td align="center"><?=$aircraft->range; ?></td>
	<td align="center"><?=$aircraft->weight; ?></td>
	<td align="center"><?=$aircraft->cruise; ?></td>
	<td align="center"><a id="dialog" class="jqModal" href="action.php?admin=editaircraft&id=<?=$aircraft->id;?>">Options</a></td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>