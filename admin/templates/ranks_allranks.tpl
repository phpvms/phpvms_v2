<h3>Pilot Ranks</h3>
<p>You can define your pilot ranks here. When a PIREP is accepted, the pilot is automatically placed into the proper ranking.</p>
<p><a id="dialog" class="jqModal" href="action.php?admin=addrank"><img src="lib/images/addrank.gif" alt="Add Rank" /></a><!-- |
	<a href="?admin=calculateranks">Recalculate Ranks</a>--></p>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Minimum Hours</th>
	<th>Rank Title</th>
	<th>Total Pilots</th>
	<th>Options</th>
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
	<td align="center"><a id="dialog" class="jqModal" href="action.php?admin=editrank&rankid=<?=$rank->rankid;?>"><img src="lib/images/edit.gif" alt="Edit" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>