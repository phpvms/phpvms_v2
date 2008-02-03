<?php

/**
 * This is a contact module
 */
 

class Contact extends ModuleBase
{

	function Controller()
	{
		//Path to our templates folder
		$this->TEMPLATE->template_path = dirname(__FILE__) . '/templates';
		
		
		
		// Main function
		if($_GET['page'] == "contact")
		{
		$this->TPL->ShowTemplate("form.tpl");		
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