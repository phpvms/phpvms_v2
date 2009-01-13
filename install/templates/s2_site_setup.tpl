<form action="?page=complete" method="post">
	<table width="550px">
	<tr>
	<td colspan="2">
		<p>Now to setup your main site. Provide your login, VA name, and your first airline. For more information,
			<a href="http://www.phpvms.net/docs/installation" target="_blank">view this page (opens in new window)</a>.</p>
		<?php 
		if($message!='')
		{
			echo '<div id="error">'.$message.'</div>';
		}
		?>		
	</td>
	</tr>
	
	<tr>
		<td><strong>Your Admin User</strong></td>
		<td></td>
	</tr>
	<tr>
		<td><strong>* Site Name: </strong></td>
		<td><input type="text" name="SITE_NAME" value="<?php echo $_POST['SITE_NAME']?>" /></td>
	</tr>
	
	<tr>
		<td><strong>* Your First Name </strong></td>
		<td><input type="text" name="firstname" value="<?php echo $_POST['firstname']?>" /></td>	
	</tr>
	
	<tr>
		<td width="1px" nowrap><strong>* Your Last Name: </strong></td>
		<td><input type="text" name="lastname" value="<?php echo $_POST['lastname']; ?>" /></td>	
	</tr>
	
	<tr>
		<td><strong>* Email: </strong></td>
		<td><input type="text" name="email" value="<?php echo $_POST['email']?>" /></td>
	</tr>
	
	<tr>
		<td><strong>* Password: </strong></td>
		<td><input type="text" name="password" value="<?php echo $_POST['password']?>" /></td>
	</tr>
	
	<tr>
		<td></td>
		<td><hr></td>
	</tr>
	
	<tr>
		<td><strong>Your Virtual Airline</strong></td>
		<td></td>
	</tr>
	
	<tr>
		<td width="1px" nowrap><strong>* Your Virtual Airline: </strong></td>
		<td><input type="text" name="vaname" value="<?php echo $_POST['vaname']?>" /></td>
	</tr>
	<tr>
		<td></td>
		<td><p align="center">This is your first/main airline. You can add more later</p></td>
	</tr>
	
	<tr>
		<td width="1px" nowrap><strong>* Your Airline's Code: </strong></td>
		<td><input type="text" name="vacode" value="<?php echo $_POST['vacode']?>" /></td>
	</tr>
	
	<tr>
		<td></td>
		<td><p align="center">This is your airline's code (ie: VMS)</p></td>
	</tr>
	
	<tr>
		<td><input type="hidden" name="action" value="submitsetup" /></td>
		<td><input type="submit" name="submit" value="Finish!" /></td>
	</tr>
</table>
</form>