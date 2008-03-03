<?php
if(!$allgroups)
{
	echo 'There are no groups';
	return;
}
?>

<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Group Name</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allgroups as $group)
{
?>
<tr>
	<td align="center"><?=$group->groupname; ?></td>
	<td align="center">Rename | Delete</td>
</tr>
<?php
}
?>
</tbody>
</table>