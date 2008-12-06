<h3>CSV Import</h3>
<p><strong>Instructions</strong> - You can import your flight plans from CSV. You can download
a template CSV from <a href="<?php echo SITE_URL ?>/admin/lib/template.csv">here</a>. The following
must be done:</p>
<ol>
	<li>The airline code must be added, or import will fail</li>
	<li>The aircraft must be added, use the "Name" entered, <strong>not</strong> the fullname or ICAO</li>
	<li>You can leave out the header, but if it is there, <strong>check off the box</strong></li>
	<li>All of the columns can be there, but only the route, leg, distance, and flighttime can be blank</li>
	<li>Routes which already exist (based on the code and flight number) will not be re-added</li>
	<li>Aircraft must be added by registration</li>
</ol>

<form enctype="multipart/form-data" action="?admin=processimport" method="post">
Choose your import file (*.csv): <input name="uploadedfile" type="file" /><br />
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
<div style="margin-left: 195px;">
	<input type="checkbox" name="header" checked /> First line of CSV is the header
	<br /><br />
	<input type="submit" value="Upload File" />
</div>
</form>