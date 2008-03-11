<h3>Add Page</h3>
<form id="form" action="action.php?admin=viewpages" method="post">
<dl>
<dt>Page Title</dt>
<dd><input name="pagename" type="text">
	<p>This will show up in the navigation bar.</p>
</dd>

<dt>Page Content</dt>
<dd><textarea name="content" id="editor" ></textarea></dd>

<dt></dt>
<dd><input type="hidden" name="action" value="addpage" />
	<input type="submit" name="submit" value="Add Page" />
</dd>
</dl>
</form>