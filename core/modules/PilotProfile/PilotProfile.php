<?php

include ADMIN_PATH . '/modules/PilotAdmin/PilotData.class.php';

class PilotProfile extends ModuleBase
{	
	function Controller()
	{
		//TODO: if not logged in, just return
		
		$this->TEMPLATE->template_path = dirname(__FILE__);
		
		if(Vars::GET('page') == 'profile')
		{
			//show our profile stuff
			
		}
	}
	
}
?>