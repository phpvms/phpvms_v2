<h3>Edit Profile</h3>
<form action="?page=editprofile" method="post">
<dl>
	<dt>Name</dt>
	<dd><?=$userinfo->firstname . ' ' . $userinfo->lastname?></dd>
	<dt>Email Address</dt>
	<dd><input type="text" name="email" value="<?=$userinfo->email;?>" />
		<?php
			if($email_error == true)
				echo '<p class="error">Please enter your email address</p>';
		?>
	</dd>
	
	<dt>Location</dt>
	<dd><input type="text" name="location" value="<?=$userinfo->location?>" />
		<?php
			if($location_error == true)
				echo '<p class="error">Please enter your location</p>';
		?>
	</dd>
	
	<dt>Password</dt>
	<dd><p>To change your password, enter your new password below</p>
		<input type="password" id="password" name="password1" value="" />
		<p>Enter your password again</p>
		<input type="password" name="password2" value="" />
		<?php
			if($password_error != '')
				echo '<p class="error">'.$password_error.'</p>';
		?>
		<p>Enter your old password</p>
		<input type="password" name="oldpassword" />
	</dd>
	
	<?php
	if($customfields)
	{
		foreach($customfields as $field)
		{
			echo '<dt>'.$field->fieldname.'</dt>
				  <dd><input type="text" name="'.$field->fieldname.'" value="'.$field->value.'" /></dd>';
		}
	}
	?>
	
	<dt></dt>
	<dd><input type="hidden" name="action" value="saveprofile" />
		<input type="submit" name="submit_register" value="Save Changes" /></dd>
</dl>
</form>