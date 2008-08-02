<h3>Custom PIREP Fields</h3>
<?php
if(!$allfields)
{
	echo '<p>You have not added any custom PIREP fields</p><br />';
	return;
}
?>

<table id="tabledlist" class="tablesorter">
<thead>
	<tr>
		<th>Field Name</th>
		<th>Values</th>
		<th>Options</th>
	</tr>
</thead>
<tbody>
<?php
foreach($allfields as $field)
{
?>
<tr>
	<td align="center"><?=$field->title;?></td>
	<td align="center">
	<?php
		if($field->type == '')
			echo '-';
		elseif($field->type == 'text')
			echo '<input type="text" name="" value="'.$field->options.'" />';
		elseif($field->type == 'dropdown')
		{
			$values = explode(',', $field->options);
			
			echo '<select name="'.$field->name.'">';
			foreach($values as $value)
			{
				$value = trim($value);
				echo '<option value="'.$value.'">'.$value.'</option>';
			}
			echo '</select>';	
		}
	?>
	</td>
	<td align="center"><a href="action.php?admin=pirepfields" action="deletefield" id="<?=$field->fieldid;?>" class="ajaxcall"><img src="lib/images/delete.gif" alt="Delete" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>