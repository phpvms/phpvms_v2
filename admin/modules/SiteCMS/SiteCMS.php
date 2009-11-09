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
	
	public function viewnews()
	{
		$isset = isset($this->post->action);
		if($isset && $this->post->action == 'addnews')
		{
			$this->AddNewsItem();		
		}
		elseif($isset && $this->post->action == 'editnews')
		{
			$res = SiteData::EditNewsItem($this->post->id, $this->post->subject, $this->post->body);
			
			if($res == false)
			{
				Template::Set('message', Lang::gs('news.updated.error'));
				Template::Show('core_error.tpl');
			}
			else
			{
				Template::Set('message', Lang::gs('news.updated.success'));
				Template::Show('core_success.tpl');
			}
		}
		elseif($isset && $this->post->action == 'deleteitem')
		{	
			$this->DeleteNewsItem();	
		}
		
		$allnews = SiteData::GetAllNews();
		Template::Set('allnews', $allnews);
		Template::Show('news_list.tpl');
		
	}
	
	public function addnews()
	{
		Template::Set('title', Lang::gs('news.add.title'));
		Template::Set('action', 'addnews');
		
		Template::Show('news_additem.tpl');
	}
	
	public function editnews()
	{
		Template::Set('title', Lang::gs('news.edit.title'));
		Template::Set('action', 'editnews');
		Template::Set('newsitem', SiteData::GetNewsItem($this->get->id));
		
		Template::Show('news_additem.tpl');
	}
	
	public function addpageform()
	{
		
		Template::Set('title', Lang::gs('page.add.title'));
		Template::Set('action', 'addpage');
		
		Template::Show('pages_editpage.tpl');
	}
	
	public function editpage()
	{
		$pageid = $this->get->pageid;
		
		$page = SiteData::GetPageData($pageid);
		Template::Set('pagedata', $page);
		Template::Set('content', @file_get_contents(PAGES_PATH . '/' . $page->filename . PAGE_EXT));
		
		Template::Set('title', Lang::gs('page.edit.title'));
		Template::Set('action', 'savepage');
		Template::Show('pages_editpage.tpl');
	}
	
	public function deletepage()
	{
		$pageid = $this->get->pageid;
				
		if(SiteData::DeletePage($pageid) == false)
		{
			Template::Set('message', Lang::gs('page.error.delete'));
			Template::Show('core_error.tpl');
		}
		else
		{
			Template::Set('message', Lang::gs('page.deleted'));
			Template::Show('core_success.tpl');
		}
	}
	
	public function viewpages()
	{
		
		/* This is the actual adding page process
				 */
		if(isset($this->post->action))
		{
			switch($this->post->action)
			{
				case 'addpage':
					$this->add_page_post();
					break;
				case 'savepage':
					$this->edit_page_post();
					break;
			}
		}
		
		/* this is the popup form edit form
		 */
		switch($this->get->action)
		{
			case 'editpage':
		
				$this->edit_page_form();
				return;
				
				break;
			case 'deletepage':
		
				$pageid = $this->get->pageid;
				SiteData::DeletePage($pageid);
				
				break;
		}
		
		
		Template::Set('allpages', SiteData::GetAllPages());
		Template::Show('pages_allpages.tpl');
	}
	
	/**
	 * This is the function for adding the actual page
	 */
	protected function add_page_post()
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
				Template::Set('message', Lang::gs('page.exists'));
			}
			else
			{
				Template::Set('message', Lang::gs('page.create.error'));
			}
			
			Template::Show('core_error.tpl');
		}

		Template::Set('message', 'Page Added!');
		Template::Show('core_success.tpl');
	}
	
	protected function edit_page_post()
	{
		$pageid = $this->post->pageid;
		$content = $this->post->content;
		$public = ($this->post->public == 'true') ? true : false;
		$enabled = ($this->post->enabled == 'true') ? true : false;
		
		if(!SiteData::EditFile($pageid, $content, $public, $enabled))
		{
			Template::Set('message', Lang::gs('page.edit.error'));
			Template::Show('core_error.tpl');
		}
		
		Template::Set('message', 'Content saved');
		Template::Show('core_success.tpl');
	}
				
	
	protected function AddNewsItem()
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
	
	protected function DeleteNewsItem()
	{
		if(!SiteData::DeleteItem($this->post->id))
		{
			Template::Set('message', Lang::gs('news.delete.error'));
			Template::Show('core_error.tpl');
			return;
		}
		
		Template::Set('message', Lang::gs('news.item.deleted'));
		Template::Show('core_success.tpl');
	}
}