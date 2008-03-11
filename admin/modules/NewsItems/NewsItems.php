<?php

class NewsItems
{
	
	function NavBar()
	{
		echo '<li><a href="#">site</a>
					<ul>
						<li><a href="?admin=viewnews">View News</a></li>
						<li><a href="?admin=editpages">Site Pages</a></li>
					</ul>
				</li>';
	}
	
	function Controller()
	{
		if(isset($_POST['addnews']))
		{
			$this->AddNewsItem();
		}
		
		switch(Vars::GET('admin'))
		{
			case 'viewnews':
			
				if(Vars::POST('action') == 'deleteitem')
				{
					$this->DeleteNewsItem();
				}
				
				$this->ViewNews();
				$this->AddNewsForm();
				break;
			case 'addnews':
				$this->AddNewsForm();
				break;
				
			case 'editpages':
				$this->EditPages();
			
				break;
		}
	}
	
	function EditPages()
	{
		
	}
	
	function ViewNews()
	{
		$allnews = SiteData::GetAllNews();
			
		Template::Set('allnews', $allnews);
		Template::Show('news_list.tpl');
	}

	function AddNewsForm()
	{
		Template::Show('news_additem.tpl');
	}	
	
	function AddNewsItem()
	{
		$subject = Vars::POST('subject');
		$body = Vars::POST('body');
		
		if($subject == '')
			return;
		
		if($body == '')
			return;
			
		if(!SiteData::AddNewsItem($subject, $body))
		{
			Template::Set('message', 'There was an error adding the news item');
		}
		
		Template::Show('core_message.tpl');
	}
	
	function DeleteNewsItem()
	{	
		if(SiteData::DeleteItem(Vars::POST('id')))
			Template::Set('message', 'News item deleted');
		else
			Template::Set('message', 'There was an error deleting the item');
			
		Template::Show('core_message.tpl');
	}
}

?>