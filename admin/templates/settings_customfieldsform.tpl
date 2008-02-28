<h1>Custom Fields</h1>
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
	<dt><?=$field->fieldname;?></dt>
	<dd><a href="action.php" module="customfields" action="deletefield" id="<?=$field->fieldid;?>" class="ajaxcall">Delete</a></dd>
<?php
}
?>
</dl>
<br /><br />
<hr>