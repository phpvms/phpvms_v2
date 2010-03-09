<?php
	$siteurl = str_replace('/install/install.php', '', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
?>
<h2>Database Setup</h2>
<form action="?page=installdb" method="post" align="center">
	<table width="550px" align="center">
	<tr>
	<td colspan="2">
		Welcome to the phpVMS installer! Enter your database information below. You must have the following
			permissions on your database: SELECT, UPDATE, DELETE, ALTER, CREATE. For more information,
			<a href="http://www.phpvms.net/docs/installation" target="_blank">view this page (opens in new window)</a>.
		<?php 
		if($message!='')
		{
			echo '<div id="error">'.$message.'</div>';
		}
		?>
		<br /><br /><br />
	</td>
	</tr>
	<tr>
		<td align="right" valign="top"><strong>Website URL: * </strong></td>
		<td>
			<input type="text" name="SITE_URL" value="<?php echo $_POST['SITE_URL']==''?$siteurl:$_POST['SITE_URL'];?>" />
			<p>The URL to your base phpVMS install.</p>
		</td>	
	</tr>
	<tr>
		<td align="right" valign="top"><strong>Database Name: * </strong></td>
		<td><input type="text" id="DBASE_NAME" name="DBASE_NAME" value="<?php echo $_POST['DBASE_NAME']?>" />
			<p>Enter the name of the database.</p>
		</td>	
	</tr>
	<tr>
		<td align="right" valign="top"><strong>Database Server: * </strong></td>
		<td><input type="text" id="DBASE_SERVER" name="DBASE_SERVER" 
				value="<?php echo $_POST['DBASE_SERVER']==''?'localhost':$_POST['DBASE_SERVER']; ?>" />
			<p>Enter the address to your database (usually <i>localhost</i>).</p>
		</td>	
	</tr>
	<tr>
		<td align="right" width="1px" nowrap valign="top"><strong>Database Username: * </strong></td>
		<td><input type="text" id="DBASE_USER" name="DBASE_USER" value="<?php echo $_POST['DBASE_USER']?>" />
			<p>Enter the username to access your database.</p>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top"><strong>Database Password: * </strong></td>
		<td><input type="text" id="DBASE_PASS" name="DBASE_PASS" value="<?php echo $_POST['DBASE_PASS']?>" />
			<p>Enter the password to access your database.</p>
		</td>	
	</tr>
	<tr>
		<td align="right" valign="top"><strong>Database Type: * </strong></td>
		<td>
			<select id="DBASE_TYPE" name="DBASE_TYPE">
				<option value="mysql">MySQL</option>
				<option value="mysqli">MySQLi (Extended)</option>
			</select>
			<p>Select the database connect ("MySQL" is your best bet).</p>
		</td>	
	</tr>
	<tr>
		<td align="right" valign="top"><strong>Table Prefix: </strong></td>
		<td>
			<input type="text" name="TABLE_PREFIX" 
				value="<?php echo $_POST['TABLE_PREFIX']==''?'phpvms_':$_POST['TABLE_PREFIX'];?>" />
			<p>If you share this database with another application, use a prefix to the tables, so there
				are no collisions.</p>
		</td>	
	</tr>
		
	<tr>
		<td></td>
		<td align="center">
			<div id="dbtest"></div><br />
			<input type="button" id="dbcheck" value="Check Database Connection" />
		</td>	
	</tr>
	<tr>
		<td><input type="hidden" name="action" value="submitdb" /></td>
		<td align="center"><input type="submit" name="submit" value="Next Step" /></td>
	</tr>
</table>
</form>