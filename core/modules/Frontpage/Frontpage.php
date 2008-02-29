<?php


dirname(__FILE__) . '/News.php';

class Frontpage extends ModuleBase
{
	function Controller()
	{
		// Assume we're on the front page is no page is set
		if(Vars::GET('page') == '')
		{
			// This organizes the items on the front-page
			Template::Show('frontpage_main.tpl');
		}
	}
}