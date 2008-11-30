<h3>Edit Profile</h3>
<form action="<?php echo SITE_URL?>/index.php/profile" method="post">
<dl>
	<dt>Name</dt>
	<dd><?php echo $userinfo->firstname . ' ' . $userinfo->lastname?></dd>
	<dt>Email Address</dt>
	<dd><input type="text" name="email" value="<?php echo $userinfo->email;?>" />
		<?php
			if($email_error == true)
				echo '<p class="error">Please enter your email address</p>';
		?>
	</dd>
	
	<dt>Location</dt>
	<dd><input type="text" name="location" value="<?php echo $userinfo->location?>" />
		<?php
			if($location_error == true)
				echo '<p class="error">Please enter your location</p>';
		?>
	</dd>
	
	<?php
	if($customfields)
	{
		foreach($customfields as $field)
		{
			echo '<dt>'.$field->title.'</dt>
				  <dd><input type="text" name="'.$field->fieldname.'" value="'.$field->value.'" /></dd>';
		}
	}
	?>
	
	<dt></dt>
	<dd><input type="hidden" name="action" value="saveprofile" />
		<input type="submit" name="submit" value="Save Changes" /></dd>
</dl>
</form>