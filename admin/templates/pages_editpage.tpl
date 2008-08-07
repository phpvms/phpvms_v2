<h3>Edit Page</h3>
<form action="index.php?admin=viewpages" method="post">

<p><strong>Page name: </strong>
<?php
if($action == 'addpage')
	echo '<input name="pagename" type="text">';
else
	echo $pagedata->pagename;
?>
</p>

<?php
if($pagedata->public == 1)
	$public = 'checked';
else
	$public = '';
	
if($pagedata->enabled == 1 || !$pagedata)
	$enabled =  'checked';
else
	$enabled = '';
?>
<p><strong>Page Content</strong></p>
<p><textarea name="content" id="editor" style="width: 550px; height: 250px;"><?=$content;?></textarea></p>
<p><strong>Public?</strong> <input type="checkbox" name="public" value="true" <?=$public?> />  <strong>Enabled?</strong><input type="checkbox" name="enabled" value="true" <?=$enabled?> /></p>
<p> <input type="hidden" name="pageid" value="<?=$pagedata->pageid;?>" />
	<input type="hidden" name="action" value="<?=$action;?>" />
	<input type="submit" name="submit" value="Save Changes" /></p>
</form>