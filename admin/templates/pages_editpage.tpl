<h3>Edit Page</h3>
<form action="?admin=viewpages" method="post">

<p><strong>Page name: </strong>
<?php
if($action == 'addpage')
	echo '<input name="pagename" type="text"> - This will show up in the navigation bar';
else
	echo $pagedata->pagename;
?>
</p>

<?php
if($pagedata->public == 1)
	$public = 'checked';
else
	$public = '';
	
if($pagedata->enabled == 1)
	$enabled =  'checked';
else
	$enabled = '';
?>
<p><strong>Public?</strong> <input type="checkbox" name="public" value="true" <?=$public?> />
	<br />If it's public, then you don't have to be logged in to see it</p>
	
<p><strong>Enabled?</strong><input type="checkbox" name="enabled" value="true" <?=$enabled?> />
	<br />You can disable this page from showing</p>
	
<p><strong>Page Content</strong></p>
<p>This is a raw editor. Any PHP code must be added in the file itself, in core/pages.</p>
<p><textarea name="content" id="editor" cols="80" rows="20"><?=$content;?></textarea></p>
<p> <input type="hidden" name="pageid" value="<?=$pagedata->pageid;?>" />
	<input type="hidden" name="action" value="<?=$action;?>" />
	<input type="submit" name="submit" value="Save Changes" /></p>
</form>