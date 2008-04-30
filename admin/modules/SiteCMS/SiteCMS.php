<?php

class SiteCMS
{
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
				
				break;
				
			case 'addnews':
				Template::Show('news_additem.tpl');
				break;
			
			case 'addpageform':

				Template::Set('title', 'Add Page');
				Template::Set('action', 'addpage');
				
				Template::Show('pages_editpage.tpl');
				break;
				
			case 'viewpages':
			
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
				
				
				/* this is the popup form edit form
				 */
				if(Vars::GET('action') == 'editpage')
				{
					$this->EditPageForm();
					return;
				}
				
				
				$this->ViewPages();
				
				break;
		}
	}
	
	/**
	 * This is the function for adding the actual page
	 */
	function AddPage()
	{
		$title = Vars::POST('pagename');
		$content = $_POST['content'];
		
		if(!$title)
		{
			Template::Set('message', 'You must have a title');
			Template::Show('core_error.tpl');
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
			
			Template::Show('core_error.tpl');
		}	

		Template::Set('message', 'Page Added!');
		Template::Show('core_success.tpl');
	}
	
	function EditPage()
	{
		$pageid = Vars::POST('pageid');
		$content = $_POST['content']; // Vars::POST('content'); // WE want this raw
		
		if(!SiteData::EditFile($pageid, $content))
		{
			Template::Set('message', 'There was an error saving content');
			Template::Show('core_error.tpl');
		}
		
		Template::Set('message', 'Content saved');
		Template::Show('core_success.tpl');
	}
				
	
	function EditPageForm()
	{
		$pageid = Vars::GET('pageid');
		
		$page = SiteData::GetPageData($pageid);
		Template::Set('pagedata', $page);
		Template::Set('content', @file_get_contents(PAGES_PATH . '/' . $page->filename . PAGE_EXT));
		
		Template::Set('title', 'Edit Page');
		Template::Set('action', 'savepage');
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