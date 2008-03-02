<?php

class Login extends ModuleBase
{
		
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