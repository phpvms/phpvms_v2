<h3>Custom Fields</h3>
<?php
if(!$allfields)
{
	echo '<p>You have not added any custom fields</p><br />';
	return;
}
?>

<table id="tabledlist" class="tablesorter">
<thead>
	<tr>
		<th>Field Name</th>
		<th>Options</th>
	</tr>
</thead>
<tbody>
<?php
foreach($allfields as $field)
{
?>
<tr>
	<td align="center"><?php echo $field->title;?></td>
	<td align="center">
		<a id="dialog" class="jqModal" 
				href="<?php echo SITE_URL?>/admin/action.php/settings/editfield?id=<?php echo $field->fieldid;?>">
			<img src="lib/images/edit.gif" alt="Edit" /></a>
			
		<a href="<?php echo SITE_URL?>/admin/action.php/settings/customfields" 
				action="deletefield" id="<?php echo $field->fieldid;?>" class="ajaxcall">
			<img src="lib/images/delete.gif" alt="Delete" />
		</a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>