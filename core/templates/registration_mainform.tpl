<h1><?php echo SITE_NAME; ?> Registration</h1>
<p>Welcome to the registration form for <?php echo SITE_NAME; ?>. 
After filling out the form below, you will get a confirmation email.</p>
<form method="post" action="index.php?page=register">
<dl>
	<dt>First Name:</dt>
	<dd><input type="text" name="firstname" value="<?=Vars::POST('firstname');?>" />
		<?php
			if($firstname_error == true)
				echo '<p class="error">Please enter your first name</p>';
		?>
	</dd>
	
	<dt>Last Name:</dt>
	<dd><input type="text" name="lastname" value="<?=Vars::POST('lastname');?>" />
		<?php
			if($lastname_error == true)
				echo '<p class="error">Please enter your last name</p>';
		?>
	</dd>
	
	<dt>Email Address</dt>
	<dd><input type="text" name="email" value="<?=Vars::POST('email');?>" />
		<?php
			if($email_error == true)
				echo '<p class="error">Please enter your email address</p>';
		?>
	</dd>
	
	<dt>Location</dt>
	<dd><input type="text" name="location" value="<?=Vars::POST('location');?>" />
		<?php
			if($location_error == true)
				echo '<p class="error">Please enter your location</p>';
		?>
	</dd>
	
	<dt>Password</dt>
	<dd><input id="password" type="text" name="password1" value="" /></dd>
	
	<dt>Enter your password again</dt>
	<dd><input type="text" name="password2" value="" />
		<?php
			if($password_error != '')
				echo '<p class="error">$password_error</p>';
		?>
	</dd>
		
	<?php
	
	//Put this in a seperate template. Shows the Custom Fields for registration
	Template::Show('registration_customfields.tpl');
	
	?>
		
	<dt></dt>
	<dd><input type="checkbox" name="agree" value="<?=Vars::POST('firstname');?>" />
		<p>I agree with the terms and conditions</p>
		<?php
			if($agree_error == true)
				echo '<p class="error">You didn\'t agree to the terms and conditions</p>';
		?>
	</dd>
	<dt></dt>
	<dd><input type="submit" name="submit_register" value="Register!" /></dd>
</dl>
</form>
