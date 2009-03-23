<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @package module_admin_sitecms
 */
 
class SiteCMS extends CodonModule
{
	function HTMLHead()
	{
		switch($this->get->page)
		{
			case 'addnews':
			case 'viewnews':
				
				Template::Set('sidebar', 'sidebar_news.tpl');
				
				break;
			
			case 'viewpages':
			
				Template::Set('sidebar', 'sidebar_pages.tpl');
				
				break;
				
			case 'addpageform':
				
				Template::Set('sidebar', 'sidebar_addpage.tpl');
				
				break;
		}
	}
	
	function Controller()
	{
		
		switch($this->get->page)
		{
			case 'deletepage':
				$pageid = $this->get->pageid;
				
				if(SiteData::DeletePage($pageid) == false)
				{
					Template::Set('message', 'There was an error deleting the page!');
					Template::Show('core_error.tpl');
				}
				else
				{
					Template::Set('message', 'The page was deleted');
					Template::Show('core_success.tpl');
				}
				
				break;
				
			case 'viewnews':
			
				switch ($this->post->action)
				{
					case 'addnews':
				
						$this->AddNewsItem();
						
						break;
						
					case 'editnews':
						
						$res = SiteData::EditNewsItem($this->post->id, $this->post->subject, $this->post->body);
						
						if($res == false)
						{
							Template::Set('message', 'There was an error editing the news item: '.DB::error());
							Template::Show('core_error.tpl');
						}
						else
						{
							Template::Set('message', 'News edited successfully!');
							Template::Show('core_success.tpl');
						}						
						break;
						
					case 'deleteitem':
						
						$this->DeleteNewsItem();
						
						break;
				}
				
				$this->ViewNews();
				
				break;
				
			case 'addnews':
				Template::Set('title', 'Add News');
				Template::Set('action', 'addnews');
				
				Template::Show('news_additem.tpl');
				break; 
				
			case 'editnews':
				
				Template::Set('title', 'Edit News');
				Template::Set('action', 'editnews');
				Template::Set('newsitem', SiteData::GetNewsItem($this->get->id));
				
				Template::Show('news_additem.tpl');
				
				break;
			
			case 'addpageform':

				Template::Set('title', 'Add Page');
				Template::Set('action', 'addpage');
				
				Template::Show('pages_editpage.tpl');
				break;
				
			case 'editpage':
				
				$this->EditPageForm();
				
				break;
			case 'viewpages':
						
				/* This is the actual adding page process
				 */
				switch($this->post->action)
				{
					case 'addpage':
						$this->AddPage();
						break;
					case 'savepage':
						$this->EditPage();
						break;
				}
				
				
				/* this is the popup form edit form
				 */
				switch($this->get->action)
				{
					case 'editpage':
				
						$this->EditPageForm();
						return;
						
						break;
					case 'deletepage':
				
						$pageid = $this->get->pageid;
						SiteData::DeletePage($pageid);
						
						break;
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
		$title = $this->post->pagename;
		$content = $this->post->content;
		$public = ($this->post->public == 'true') ? true : false;
		$enabled = ($this->post->enabled == 'true') ? true : false;
		
		if(!$title)
		{
			Template::Set('message', 'You must have a title');
			Template::Show('core_error.tpl');
			return;
		}
		
		$content = stripslashes($content);
		if(!SiteData::AddPage($title, $content, $public, $enabled))
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
		$pageid = $this->post->pageid;
		$content = $this->post->content; // Vars::POST('content'); // WE want this raw
		$public = ($this->post->public == 'true') ? true : false;
		$enabled = ($this->post->enabled == 'true') ? true : false;
		
		if(!SiteData::EditFile($pageid, $content, $public, $enabled))
		{
			Template::Set('message', 'There was an error saving content');
			Template::Show('core_error.tpl');
		}
		
		Template::Set('message', 'Content saved');
		Template::Show('core_success.tpl');
	}
				
	
	function EditPageForm()
	{
		$pageid = $this->get->pageid;
		
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
		$subject = $this->post->subject;
		$body = $this->post->body;
		
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
	
	function EditNewsItem()
	{
		
		
		
	}
	
	function DeleteNewsItem()
	{
		if(!SiteData::DeleteItem($this->post->id))
		{
			Template::Set('message', 'There was an error deleting the item');
			Template::Show('core_error.tpl');
			return;
		}
		
		Template::Set('message', 'News item deleted');
		Template::Show('core_success.tpl');
	}
}