<h3>User Groups</h3>
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
	<td align="center"><?php echo $group->name; ?></td>
	<td align="center"><?php echo $group->groupid; ?></td>
	<td align="center" width="1%" nowrap>
	<?php
	if($group->name!='Administrators')
	{
	?>
		<a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/editgroup/?groupid=<?php echo $group->groupid?>">
					<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>	
	<?php
	}
	else
		echo 'This group cannot be renamed or deleted';
	?>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<br />