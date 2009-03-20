<h3>Mass Mailer</h3>
<form method="post" action="<?php echo SITE_URL ?>/admin/index.php/MassMailer">
  <table width='100%' border='0'>
    <!--Getting e-mail addresses will be handled in MassMailer.php-->
	

	<tr>
		<td><strong>Subject: </strong></td>
		<td><input type="text" name="subject" value=""</td>
	
	</tr>
    <tr>
      <td><strong>Message:</strong></td>
      <td>
		<textarea name="message" cols='60' rows='8'></textarea>
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