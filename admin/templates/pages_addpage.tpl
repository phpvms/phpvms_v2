<h3>Add Page</h3>
<form id="form" action="action.php?admin=viewpages" method="post">
<p><strong>Page Title:</strong> <input name="pagename" type="text"> - This will show up in the navigation bar</p>
<p><strong>Page Content:</strong></p>
<p><textarea name="content" id="editor" style="width: 99%;"></textarea></p>

<p align="right">
	<input type="hidden" name="action" value="addpage" />
	<input type="submit" name="submit"  value="Add Page" />
</p>
</form>