<h3>Airlines List</h3>
<?php
if(!$allairlines)
{
	echo '<p id="error">No airlines have been added</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Code</th>
	<th>Name</th>
	<th>Enabled</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allairlines as $airline)
{
?>
<tr>
	<td align="center"><?php echo $airline->code; ?></td>
	<td align="center"><?php echo $airline->name; ?></td>
	<td align="center"><?php echo ($airline->enabled == 1) ? 'Yes' : 'No'; ?></td>
	<td align="center">
	<a id="dialog" class="jqModal" href="action.php?admin=editairline&id=<?php echo $airline->id;?>">
		<img src="lib/images/edit.gif" alt="Edit" /></a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>