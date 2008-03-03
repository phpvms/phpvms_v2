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
			
				if(Vars::POST('action') == 'changepassword')
				{
					$this->ChangePassword();
					return;
				}
				
				if(Vars::GET('action') == 'viewoptions')
				{
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
		Template::Set('pilotinfo', PilotData::GetPilotData(Vars::GET('userid')));
		
		Template::Show('pilots_detailtabs.tpl');
	}
	
	function ChangePassword()
	{
		$password1 = Vars::POST('password1');
		$password2 = Vars::POST('password2');
		
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
		
		RegistrationData::ChangePassword(Vars::POST('userid'), $password1);
	}
}

?>