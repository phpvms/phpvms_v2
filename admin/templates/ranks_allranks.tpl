<h3>Pilot Ranks</h3>
<p>You can define your pilot ranks here. When a PIREP is accepted, the pilot is automatically placed into the proper ranking.</p>
<p><a id="dialog" class="jqModal" href="action.php?admin=addrank">Add a rank</a> | 
	<a href="?admin=calculateranks">Recalculate Ranks</a></p>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Minimum Hours</th>
	<th>Rank Title</th>
	<th>Total Pilots</th>
</tr>
</thead>
<tbody>
<?php
foreach($ranks as $rank)
{
?>
<tr>
	<td align="center"><?=$rank->minhours; ?></td>
	<td align="center"><?=$rank->rank; ?></td>
	<td align="center"><?=$rank->totalpilots; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>