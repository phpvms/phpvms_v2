<?php

class PilotAdmin
{
	function NavBar()
	{
		echo '<li><a href="#">Pilots</a>
				<ul>
					<li><a href="?admin=viewpilots">View Registered Pilots</a></li>
				</ul>
				</li>';
	}
	
	function Controller()
	{
		switch(Vars::GET('admin'))
		{
			case 'viewpilots':

				/* This function is called for *ANYTHING* in that popup box
					
					Preset all of the template items in this function and 
					call them in the subsequent templates
					
					Confusing at first, but easier than loading each tab 
					independently via AJAX. Though may be an option later 
					on, but can certainly be done by a plugin (Add another
					tab through AJAX). The hook is available for whoever 
					wants to use it
				*/
				if(Vars::GET('action') == 'viewoptions')
				{
					if(Vars::POST('action') == 'changepassword')
					{
						$this->ChangePassword();
						return;
					}
					
					$this->ViewPilotDetails();	
					return;
				}
				
				$this->ShowPilotsList();	
			break;	
		}
		
	}
	
	function ShowPilotsList()
	{
		$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 
						 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 
						 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		
		Template::Set('allletters', $letters);
		Template::Set('allpilots', PilotData::GetAllPilots(Vars::GET('letter')));
		
		Template::Show('pilots_list.tpl');
	}
	
	function ViewPilotDetails()
	{
		$userid = Vars::GET('userid');
		
		//This is for the main tab
		Template::Set('pilotinfo', PilotData::GetPilotData($userid));
		Template::Set('customfields', PilotData::GetFieldData($userid, true));
		
		//This is for the groups tab
		Template::Set('allgroups', PilotData::GetPilotGroups($userid));
		
		Template::Show('pilots_detailtabs.tpl');
	}
	
	function ChangePassword()
	{
		$password1 = Vars::POST('password1');
		$password2 = Vars::POST('password2');
		
		echo '<div id="messagebox">';
		
		// Check password length
		if(strlen($password1) <= 5)
		{
			echo 'Password is less than 5 characters';
			return;
		}
		
		// Check is passwords are the same	
		if($password1 != $password2)
		{
			echo 'The passwords do not match';
			return;
		}
		
		if(RegistrationData::ChangePassword(Vars::POST('userid'), $password1))
			echo 'Password has been successfully changed';
		else
			echo 'There was an error, administrator has been notified';
			
		
		echo '</div>';
	}
}

?>