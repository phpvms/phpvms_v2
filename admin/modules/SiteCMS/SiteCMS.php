<?php

class SiteCMS
{
	
	function NavBar()
	{
		echo '<li><a href="#">site</a>
					<ul>
						<li><a href="?admin=viewnews">View News</a></li>
						<li><a href="?admin=viewpages">Site Pages</a></li>
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
			
			case 'addpageform':
				$this->AddPageForm();
				break;
				
			case 'viewpages':
			
				if(Vars::GET('action') == 'editpage')
				{
					$this->EditPageForm();
					return;
				}
				
				if(Vars::POST('action') == 'addpage')
				{
					$this->AddPage();
				}
				elseif(Vars::POST('action') == 'savepage')
				{
					$this->EditPage();
					return;
				}
				
				$this->ViewPages();
				
				$this->AddPageForm();
				break;
		}
	}
	
	function AddPageForm()
	{
		Template::Show('pages_addpage.tpl');
	}
	
	function AddPage()
	{
		$title = Vars::POST('pagename');
		$content = Vars::POST('content');
		
		if(!$title || !$content)
		{
			Template::Set('message', 'You must fill out all of the fields');
			Template::Show('core_message.tpl');
			return;
		}
		
		if(!SiteData::AddPage($title, $content))
		{
			if(DB::$errno == 1062)
			{
				Template::Set('message', 'This page already exists!');
			}
			else
			{
				Template::Set('message', 'There was an error creating the file');
			}
			
			Template::Show('core_message.tpl');
		}			
	}
	
	function EditPage()
	{
		$pageid = Vars::POST('pageid');
		$content = Vars::POST('pageid');
		
		if(SiteData::EditFile($pageid, $content))
		{
			Template::Set('message', 'Content saved');
		}
		else
		{
			Template::Set('message', 'There was an error saving content');
		}
		
		Template::Show('core_message.tpl');
	}
				
	
	function EditPageForm()
	{
		$pageid = Vars::GET('pageid');
		
		Template::Set('pagedata', SiteData::GetPageData($pageid));
		Template::Show('pages_editpage.tpl');
	}
	
	function ViewPages()
	{
		Template::Set('allpages', SiteData::GetAllPages());
		
		Template::Show('pages_allpages.tpl');
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