<?php



class Pages extends ModuleBase
{
	

	function Navigation()
	{
		
		
		Template::Set('allpages', SiteData::GetAllPages(true));
		Template::Show('pages_items.tpl');	
		
	}	
	
	
	function Controller()
	{

		if(Vars::GET('page') == 'content' && isset($_GET['p']))
		{
			$page = Vars::GET('p');
			
			echo SiteData::GetPageContent($page);
			
		}
	}
}
?>