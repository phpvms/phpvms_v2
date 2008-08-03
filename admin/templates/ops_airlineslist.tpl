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
	<?php
		//<th>Options</th>
	?>
</tr>
</thead>
<tbody>
<?php
foreach($allairlines as $airline)
{
?>
<tr>
	<td align="center"><?=$airline->code; ?></td>
	<td align="center"><?=$airline->name; ?></td>
	<?php	/*<td align="center">
	<a id="dialog" class="jqModal" href="action.php?admin=editairline&id=<?=$airline->id;?>">
		<img src="lib/images/options.gif" alt="Options" /></a>
	</td>*/ ?>
</tr>
<?php
}
?>
</tbody>
</table>