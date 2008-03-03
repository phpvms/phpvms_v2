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
	<th>Group ID</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allgroups as $group)
{
?>
<tr>
	<td align="center"><?=$group->name; ?></td>
	<td align="center"><?=$group->groupid; ?></td>
	<td align="center">
	<?php
	if($group->name!='Administrators')
	{
	?>
	Rename | Delete
	<?php
	}
	?>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>