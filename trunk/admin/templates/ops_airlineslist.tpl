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
	<td align="center" width="1%" nowrap>
	<a id="dialog" class="jqModal" 
			href="<?php echo SITE_URL?>/admin/action.php/operations/editairline?id=<?php echo $airline->id;?>">
		<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>