<?php

class Registration extends ModuleBase
{
	
	function NavBar()
	{
		//TODO: only show if logged out
		echo '<li><a href="#">Register</a></li>';
	}
	
	function Controller()
	{
		$this->TEMPLATE->template_path = dirname(__FILE__);
		
		if(Vars::GET('page') == 'register')
		{
			
			//show our registration stuff
			
		}
	}
	
}
?>