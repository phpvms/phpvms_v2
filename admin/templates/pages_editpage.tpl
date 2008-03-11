<h3>Edit Page</h3>
<form id="form" action="action.php?admin=viewpages" method="post">

<p><strong>Page name: </strong><?=$pagedata->pagename;?></p>
<p><strong>Page Content</strong></p>
<p><textarea name="content" id="editor" style="width: 90%;"><?=$content;?></textarea></p>
<p> <input type="hidden" name="pageid" value="<?=$pagedata->pageid;?>" />
	<input type="hidden" name="action" value="savepage" />
	<input type="submit" name="submit" value="Save Changes" /></p>
</form>