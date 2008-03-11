<?php



class Pages extends ModuleBase
{
	

	function NavBar()
	{
		
		
		Template::Set('allpages', SiteData::GetAllPages(true));
		Template::Show('pages_items.tpl');	
		
	}	
	
	
	function Controller()
	{

		if(Vars::GET('page') == 'content' && isset($_GET['p']))
		{
			// Page here is the filename, but we don't call it in directly
			//	for security reasons
			
			$page = Vars::GET('p');
			
			$content = SiteData::GetPageContent($page);
			if(!$content)
			{
				Template::Show('pages_notfound.tpl');
			}
			else
			{
				// Do it this way, so then that this page/template
				//	can be customized on a skin-by-skin basis
				
				Template::Set('pagename', $content->pagename);
				Template::Set('content', $content->content);
				
				Template::Show('pages_content.tpl');
			}
		}
	}
}
?>