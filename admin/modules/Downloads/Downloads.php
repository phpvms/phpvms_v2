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
 */

class Downloads extends CodonModule
{
	public function HTMLHead()
	{
		
		Template::Set('sidebar', 'sidebar_downloads.tpl');
	}
	
	public function Controller()
	{
		switch($this->get->page)
		{
			case '':
			case 'overview':
			
				switch($this->post->action)
				{
					case 'addcategory':
						$this->AddCategory();
						break;
					
					case 'editcategory':
						$this->EditCategory();
						break;
						
					case 'deletecategory':
						$this->DeleteCategory();
						break;
						
					case 'adddownload':
						$this->AddDownload();
						break;
						
					case 'editdownload':
						$this->EditDownload();
						break;
						
					case 'deletedownload':
						$this->DeleteDownload();
						break;
										
				}
			
				Template::Set('allcategories', DownloadData::GetAllCategories());
				Template::Show('downloads_overview.tpl');
				
				break;
				
			case 'addcategory':
			
				Template::Set('title', 'Add Category');
				Template::Set('action', 'addcategory');
				
				Template::Show('downloads_categoryform.tpl');
			
				break;
			
			case 'adddownload':
			
				Template::Set('title', 'Add Download');
				Template::Set('allcategories', DownloadData::GetAllCategories());
				Template::Set('action', 'adddownload');
				
				Template::Show('downloads_downloadform.tpl');
			
				break;
				
			case 'editcategory':
			
				Template::Set('title', 'Edit Category');
				Template::Set('action', 'editcategory');
				Template::Set('category', DownloadData::GetAsset($this->get->id));
				
				Template::Show('downloads_categoryform.tpl');
				
				break;
				
			case 'editdownload':
			
				Template::Set('title', 'Edit Download');
				Template::Set('action', 'editdownload');
				Template::Set('allcategories', DownloadData::GetAllCategories());
				Template::Set('download', DownloadData::GetAsset($this->get->id));
				
				Template::Show('downloads_downloadform.tpl');
			
				break;
				
			case 'deletedownload':
			
				break;
				
		}
		
	}
	
	public function AddCategory()
	{
		if($this->post->name == '')
		{
			Template::Set('message', 'No category name entered!');
			Template::Show('core_error.tpl');
			return;
		}
		
		if(DownloadData::FindCategory($this->post->name))
		{
			Template::Set('message', 'Category already exists');
			Template::Show('core_error.tpl');
			return;
		}
		
		DownloadData::AddCategory($this->post->name, '', '');
		
		Template::Set('message', 'Category added!');
		Template::Show('core_success.tpl');
	}
	
	public function EditCategory()
	{
		if($this->post->name == '')
		{
			Template::Set('message', 'No category name entered!');
			Template::Show('core_error.tpl');
			return;
		}
		
		if(DownloadData::FindCategory($this->post->name))
		{
			Template::Set('message', 'Category already exists');
			Template::Show('core_error.tpl');
			return;
		}
		
		DownloadData::EditAsset($this->post->id, $this->post->name, '', '');
		
		Template::Set('message', 'Category edited!');
		Template::Show('core_success.tpl');
		
	}
	
	public function DeleteCategory()
	{
		if($this->post->id=='')
		{
			Template::Set('message', 'Invalid category!');
			Template::Show('core_error.tpl');
			return;
		}
		
		DownloadData::RemoveCategory($this->post->id);
		
		Template::Set('message', 'Category removed!');
		Template::Show('core_success.tpl');
	}
	
	public function AddDownload()
	{
		if($this->post->name == '' || $this->post->link == '')
		{
			Template::Set('message', 'Link and name must be entered');
			Template::Show('core_error.tpl');
			return;
		}
		
		DownloadData::AddDownload($this->post->category, $this->post->name, $this->post->description,
					$this->post->link, $this->post->image);
	}
	
	public function EditDownload()
	{
		if($this->post->name == '' || $this->post->link == '')
		{
			Template::Set('message', 'Link and name must be entered!');
			Template::Show('core_error.tpl');
			return;
		}
			
		DownloadData::EditAsset($this->post->id, $this->post->name,  $this->post->category, $this->post->description, 
									$this->post->link, $this->post->image);
		
		Template::Set('message', 'Download edited!');
		Template::Show('core_success.tpl');
	}
	
	public function DeleteDownload()
	{
		if($this->post->id=='')
		{
			Template::Set('message', 'Invalid download ID!');
			Template::Show('core_error.tpl');
			return;
		}
		
		DownloadData::RemoveAsset($this->post->id);
		
		Template::Set('message', 'Download removed!');
		Template::Show('core_success.tpl');
		
	}
}