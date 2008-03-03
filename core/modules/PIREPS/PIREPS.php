<?php

class PIREPS extends ModuleBase
{
	function Controller()
	{
		$this->TEMPLATE->template_path = dirname(__FILE__);
		
		
		if(Vars::GET('page') == 'filepirep')
		{
			//TODO: show PIREP page
			
		}
		elseif(Vars::GET('page') == 'viewpireps')
		{
			//TODO: show pireps
		}		
		
	}
}
?>