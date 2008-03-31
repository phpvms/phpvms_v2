<h3>Custom Fields</h3>
<?php
if(!$allfields)
{
	echo 'You have not added any custom fields<br />';
	return;
}
?>

<dl>
	<dt>Field Name</dt>
	<dd><strong>Options</strong></dd>
<?php
foreach($allfields as $field)
{
?>
	<dt><?=$field->title;?></dt>
	<dd><a href="action.php?admin=customfields" action="deletefield" id="<?=$field->fieldid;?>" class="ajaxcall">Delete</a></dd>
<?php
}
?>
</dl>
<br /><br />
<hr>