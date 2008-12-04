<h3>Edit Profile</h3>
<form action="<?php echo SITE_URL?>/index.php/profile" method="post" enctype="multipart/form-data">
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
	<dd><select name="location">
		<?php
		foreach($countries as $countryCode=>$countryName)
		{
			if($userinfo->location == $countryCode)
				$sel = 'selected="selected"';
			else	
				$sel = '';
			
			echo '<option value="'.$countryCode.'" '.$sel.'>'.$countryName.'</option>';
		}
		?>
		</select>
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
	
	<dt>Avatar:</dt>
	<dd><input type="hidden" name="MAX_FILE_SIZE" value="<?php echo Config::Get('AVATAR_FILE_SIZE');?>" />
		<input type="file" name="avatar" size="40"> 
		<p>Your image will be resized to <?php echo Config::Get('AVATAR_MAX_HEIGHT').'x'.Config::Get('AVATAR_MAX_WIDTH');?> px</p>
	</dd>
	<dt>Current Avatar:</dt>
	<dd><?php	
			if(!file_exists(SITE_ROOT.AVATAR_PATH.'/'.$pilotcode.'.png'))
			{
				echo 'None selected';
			}
			else
			{
		?>
			<img src="<?php	echo SITE_URL.AVATAR_PATH.'/'.$pilotcode.'.png';?>" /></dd>
		<?php
		}
		?>
	<dt></dt>
	<dd><input type="hidden" name="action" value="saveprofile" />
		<input type="submit" name="submit" value="Save Changes" /></dd>
</dl>
</form>