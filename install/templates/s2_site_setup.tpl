<h2>Site Setup</h2>
<form action="?page=complete" method="post">
	<table width="550px" align="center">
	<tr>
	<td colspan="2">
		Now the final step. Provide your login, VA name, and your first airline. For more information,
			<a href="http://www.phpvms.net/docs/installation" target="_blank">view this page (opens in new window)</a>.
		<?php 
		if($message!='')
		{
			echo '<div id="error">'.$message.'</div>';
		}
		?>
		<br />
		<br />
	</td>
	</tr>
	
	<tr>
		<td><strong>Your Admin User</strong><br /><br /></td>
		<td></td>
	</tr>
	
	<!--<tr>
		<td><strong>* Site Name: </strong></td>
		<td><input type="text" name="SITE_NAME" value="<?php echo $_POST['SITE_NAME']?>" /></td>
	</tr>-->
	
	<tr>
		<td align="right"><strong>Your First Name: * </strong></td>
		<td><input type="text" name="firstname" value="<?php echo $_POST['firstname']?>" /></td>	
	</tr>
	
	<tr>
		<td align="right" width="1px" nowrap><strong>Your Last Name: *</strong></td>
		<td><input type="text" name="lastname" value="<?php echo $_POST['lastname']; ?>" /></td>	
	</tr>
	
	<tr>
		<td align="right"><strong> Email: * </strong></td>
		<td><input type="text" name="email" value="<?php echo $_POST['email']?>" /></td>
	</tr>
	
	<tr>
		<td align="right"><strong>Password: * </strong></td>
		<td><input type="text" name="password" value="<?php echo $_POST['password']?>" /></td>
	</tr>
	
	<tr>
		<td></td>
		<td><hr></td>
	</tr>
	
	<tr>
		<td><strong>Your Virtual Airline</strong><br  /><br /></td>
		<td></td>
	</tr>
	
	<tr>
		<td align="right" width="1px" nowrap valign="top"><strong>Your Virtual Airline: * </strong></td>
		<td><input type="text" name="vaname" value="<?php echo $_POST['vaname']?>" />
			<p>This is your first/main airline. You can add more later</p>
		</td>
	</tr>
	
	<tr>
		<td align="right" width="1px" nowrap valign="top"><strong>Your Airline's Code: * </strong></td>
		<td><input type="text" name="vacode" value="<?php echo $_POST['vacode']?>" />
			<p >This is your airline's code (ie: VMS)</p>
		</td>
	</tr>

	<tr>
		<td align="right" width="1px" nowrap valign="top"><strong>Google API Code: * </strong></td>
		<td><input type="text" name="googlekey" value="<?php echo $_POST['googlekey']?>" />
			<p>Get an API Key from here: <a href="http://code.google.com/apis/maps/signup.html" target="_new">Google Maps API Signup</a></p>	
		</td>
	</tr>
	
	<tr>
		<td><input type="hidden" name="action" value="submitsetup" /></td>
		<td><input type="submit" name="submit" value="Finish!" /></td>
	</tr>
</table>
</form>