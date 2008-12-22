<h3>Add Group</h3>
<form id="form" action="<?php echo SITE_URL?>/admin/action.php/pilotadmin/pilotgroups" method="post">
<dl>
	<dt>Group Name:</dt>
	<dd><input name="name" type="text" value="" /></dd>
	
	<dt></dt>
	<dd><input type="hidden" name="action" value="addgroup">
		<input type="submit" name="submit" value="Add Group" /></dd>
</dl>
</form>