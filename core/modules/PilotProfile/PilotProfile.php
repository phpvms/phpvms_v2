<?php

class PilotProfile extends ModuleBase
{	
	function Controller()
	{	
		if(Vars::GET('page') == 'profile')
		{
			
			if(!Auth::LoggedIn())
			{
				echo 'Not logged in';
				return;
			}
			
			Template::Set('userinfo', Auth::$userinfo);
			
			Template::Show('profile_main.tpl');
		}
	}
}
?>