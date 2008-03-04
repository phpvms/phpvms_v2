<?php

class PilotProfile extends ModuleBase
{	
	function Controller()
	{
		if(!Auth::LoggedIn())
		{
			echo 'Not logged in';
			return;
		}
	
		if(Vars::GET('page') == 'profile')
		{
			Template::Set('userinfo', Auth::$userinfo);
			
			Template::Show('profile_main.tpl');
		}
	}
}
?>