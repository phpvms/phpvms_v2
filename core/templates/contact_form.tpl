<h3>Contact Us</h3>
<form method="post" action="<?php echo url('/contact'); ?>">
  <table width='100%' border='0'>
    <tr>
      <td><strong>Name:</strong></td>
      <td>
		<?php
		if(Auth::LoggedIn())
		{
			echo Auth::$userinfo->firstname .' '.Auth::$userinfo->lastname;
			echo '<input type="hidden" name="name" 
					value="'.Auth::$userinfo->firstname 
							.' '.Auth::$userinfo->lastname.'" />';
		}
		else
		{
		?>
			<input type="text" name="name" value="" />
			<?php
		}
		?>
      </td>
    </tr>
    <tr>
		<td width="1%" nowrap><strong>E-Mail Address:</strong></td>
		<td>
		<?php
		if(Auth::LoggedIn())
		{
			echo Auth::$userinfo->email;
			echo '<input type="hidden" name="name" 
					value="'.Auth::$userinfo->email.'" />';
		}
		else
		{
		?>
			<input type="text" name="email" value="" />
			<?php
		}
		?>
		</td>
	</tr>
	
	<?php
	
	# This is a simple captcha thing for if they are not logged in
	if(Auth::LoggedIn() == false)
	{		
		echo '<tr>
				<td><strong>Captcha</strong></td>
				<td><p>What is the sum of '.$rand1 .' and '.$rand2.'?<br />
					<input type="text" name="captcha" value="" />
				</td>
			  </tr>';
	}
	
	?>
	<tr>
		<td><strong>Subject: </strong></td>
		<td><input type="text" name="subject" value=""</td>
	
	</tr>
    <tr>
      <td><strong>Message:</strong></td>
      <td>
		<textarea name="message" cols='45' rows='5'></textarea>
      </td>
    </tr>
    <tr>
		<td>
			<input type="hidden" name="loggedin" value="<?php echo (Auth::LoggedIn())?'true':'false'?>" />
		</td>
		<td>
          <input type="submit" name="submit" value='Send Message'>
		</td>
    </tr>
  </table>
</form>