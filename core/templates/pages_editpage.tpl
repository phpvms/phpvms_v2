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
<p><strong>Page Content</strong></p>
<p>This is a raw editor. You may also add PHP code by including it within <?php  ?> PHP tags</p>
<p><textarea name="content" id="editor" style="width: 90%; height: 250px;"><?=$content;?></textarea></p>
<p> <input type="hidden" name="pageid" value="<?=$pagedata->pageid;?>" />
	<input type="hidden" name="action" value="<?=$action;?>" />
	<input type="submit" name="submit" value="Save Changes" /></p>
</form>