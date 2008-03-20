<?php

class PilotProfile extends ModuleBase
{	
	function Controller()
	{	
		switch(Vars::GET('page'))
		{
			
			case 'profile':
				
				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}
				
				/* this comes from ?page=changepassword
				*/
				if($_POST['action'] == 'changepassword')
				{
					$this->ChangePassword();
				}
				
				Template::Set('userinfo', Auth::$userinfo);
				Template::Show('profile_main.tpl');
				break;
				
			case 'editprofile':
			
				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}
				
				if($_POST['action'] == 'saveprofile')
				{
					$this->SaveProfile();
				}
											
				Template::Set('userinfo', Auth::$userinfo);
				Template::Set('customfields', PilotData::GetFieldData(Auth::$userid, true));
						
				Template::Show('profile_edit.tpl');
				break;
			
			case 'changepassword':
			
				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}
				
				Template::Show('profile_changepassword.tpl');
				break;
				
		}
	}
	
	function SaveProfile()
	{
		$userinfo = Auth::$userinfo;
		
		// save basic fields
		$email = Vars::POST('email');
		$location = Vars::POST('location');
		
		//TODO: check email validity
		if($email == '')
		{
			return;
		}
		
		PilotData::SaveProfile(Auth::$userid, $email, $location);
		PilotData::SaveFields(Auth::$userid, $_POST);
		
		
	}
	
	function ChangePassword()
	{
		//TODO: password change		
	}
}
?>