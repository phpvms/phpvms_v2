<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package module_contact
 */

class Contact
{

	function Controller()
	{
		//Path to our templates folder		
		
		// Main function
		if($_GET['page'] == "contact")
		{
			Template::ShowTemplate("contact_form.tpl");		
		}
		
		if(isset($_POST['contact']))
		{
			//TODO: more checking, etc
			
			//Send the email....
			$to = "lorenzo.aiello@gmail.com";
			$subject = "PHP VMS - Website Contac";
			$message = "The Contact US PAGE WORKeD!!!!!";
			$from = "PHP VMS";
			$headers  = "From: PHP VMS <noreply@phpvms.net>\r\n";
			mail($to,$subject,$message,$headers);
		}

	}
	
}

?>