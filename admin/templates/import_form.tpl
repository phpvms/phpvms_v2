<form enctype="multipart/form-data" action="?admin=processimport" method="post">
<p>From here, you may import a CSV (comma separated values) form to import your schedules.
	A sample file is
Choose your import file (*.csv): <input name="uploadedfile" type="file" /><br />
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
<input type="submit" value="Upload File" />
</form>