<?php


class Dashboard extends ModuleBase
{
	
	function NavBar()
	{
		echo '<li><a href="?admin=">Dashboard</a></li>';
	}
	
	function Controller()
	{
		$this->TEMPLATE->template_path = dirname(__FILE__) . '/templates';
		
		if(Vars::GET('admin') == '')
		{
			
			$this->TEMPLATE->ShowTemplate('welcome.tpl');
			
		}
	}
}
			

?>