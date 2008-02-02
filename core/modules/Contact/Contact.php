<?php

/**
 * This is a contact module
 */
 
 
 /* Make sure this is added in the site_config.inc.php
 
 $ACTIVE_MODULES[ModuleName] = 'ThisFileName.php';
 
 */
class ModuleName extends ModuleBase
{

	function Controller()
	{
		//Path to our templates folder
		$this->TEMPLATE->template_path = dirname(__FILE__) . '/templates';
		
		
		// Main function
		if($_GET['page'] == "contact")
		{
			echo "<form name='form1' method='post' action='index.php'>
  <table width='100%' border='0'>
    <tr>
      <td>Name:</td>
      <td><label>
        <input type='text' name='name' id='name'>
      </label></td>
    </tr>
    <tr>
      <td>E-Mail Address:</td>
      <td><label>
        <input type='text' name='email' id='email'>
      </label></td>
    </tr>
    <tr>
      <td>Message:</td>
      <td><label>
        <textarea name='message' id='message' cols='45' rows='5'></textarea>
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2'><label>
        <div align='center'>
          <input type='submit' name='button' id='button' value='Send Message'>
        </div>
      </label></td>
    </tr>
  </table><input name='contact' type='hidden' value='contact'>
</form>
";
		}
				if(isset($_POST['contact']))
		{
		//Send the email....
$to = "lorenzo.aiello@gmail.com";
$subject = "PHP VMS - Website Contac";
$message = "
The Contact US PAGE WORKeD!!!!!";
$from = "PHP VMS";
$headers  = "From: PHP VMS <noreply@phpvms.net>\r\n";
mail($to,$subject,$message,$headers);
		}

	}
	
}

?>