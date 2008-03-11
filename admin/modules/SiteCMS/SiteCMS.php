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
		switch(Vars::GET('admin'))
		{
			case 'viewnews':
			
				if(isset($_POST['addnews']))
				{
					$this->AddNewsItem();
				}
				
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
			
				/* this is the popup form edit form
				 */
				if(Vars::GET('action') == 'editpage')
				{
					$this->EditPageForm();
					return;
				}
				
				/* This is the actual adding page process 
				 */
				if(Vars::POST('action') == 'addpage')
				{
					$this->AddPage();
				}
				/* This a save page update
				 */
				elseif(Vars::POST('action') == 'savepage')
				{
					$this->EditPage();
				}
				
				$this->ViewPages();
				
				$this->AddPageForm();
				break;
		}
	}
	
	/**
	 * Show the main page addition form
	 */
	function AddPageForm()
	{
		Template::Show('pages_addpage.tpl');
	}
	
	/**
	 * This is the function for adding the actual page
	 */
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
		$content = Vars::POST('content');
		
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
		
		$page = SiteData::GetPageData($pageid);
		Template::Set('pagedata', $page);
		Template::Set('content', @file_get_contents(PAGES_PATH . '/' . $page->filename . '.html'));
		
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