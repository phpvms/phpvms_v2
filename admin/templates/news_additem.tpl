<h3>Add News Item</h3>

<form id="form" action="action.php?admin=viewnews" method="post">
<dl>

	<dt><strong>Subject: </strong></dt>
	<dd><input type="text" name="subject" /></dd>
	
	<dt>News Text</dt>
	<dd><textarea id="editor" name="body" style="width: 90%;"></textarea></dd>
	
	<dt></dt>
	<dd><input type="submit" name="addnews" value="Add News" /></dd>
</dl>
</form>