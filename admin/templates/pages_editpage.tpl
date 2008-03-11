<h3>Edit Page</h3>
<form id="dialogform" action="action.php?admin=viewpages" method="post">

<p><strong>Page name: </strong><?=$pagename;?></p>
<p><strong>Page Content</strong></p>
<p><textarea name="content"><?=$content;?></textarea></p>
<p> <input type="hidden" name="pageid" value="<?=$pageid;?>" />
	<input type="hidden" name="action" value="savepage" />
	<input type="submit" name="submit" value="Save Changes" /></p>
</form>
<div id="dialogresult"></div>