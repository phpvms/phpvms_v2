<h3>Add News Item</h3>

<form id="form" action="action.php?admin=viewnews" method="post">
<p><strong>Subject: </strong><input type="text" name="subject" /></p>
<p>
	<strong>News Text: </strong><br />
	<textarea id="editor" name="body" style="width: 93%;"></textarea>
</p>	
<input type="submit" name="addnews" value="Add News" />
</form>