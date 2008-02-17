<?php


class Login extends ModuleBase
{
	
	function NavBar()
	{
		//TODO: only show if logged out
		echo '<li><a href="#">Login</a></li>';
	}
	
	function Controller()
	{
		$this->TEMPLATE->template_path = dirname(__FILE__);
		
		if(Vars::GET('page') == 'login')
		{
			
			//do our login stuff
			
		}
	}
	
}
?>