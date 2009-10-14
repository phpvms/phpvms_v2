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
	
	public function index()
	{
		$this->overview();
	}
	
	public function overview()
	{
		switch($this->post->action)
		{
			case 'addcategory':
				$this->AddCategoryPost();
				break;
			
			case 'editcategory':
				$this->EditCategoryPost();
				break;
			
			case 'deletecategory':
				$this->DeleteCategoryPost();
				break;
			
			case 'adddownload':
				$this->AddDownloadPost();
				break;
			
			case 'editdownload':
				$this->EditDownloadPost();
				break;
			
			case 'deletedownload':
				$this->DeleteDownloadPost();
				break;
			
		}
		
		Template::Set('allcategories', DownloadData::GetAllCategories());
		Template::Show('downloads_overview.tpl');
	}
	
	public function addcategory()
	{
		Template::Set('title', 'Add Category');
		Template::Set('action', 'addcategory');
		
		Template::Show('downloads_categoryform.tpl');
		
	}
	
	public function adddownload()
	{
		Template::Set('title', 'Add Download');
		Template::Set('allcategories', DownloadData::GetAllCategories());
		Template::Set('action', 'adddownload');
		
		Template::Show('downloads_downloadform.tpl');
	}
	
	public function editcategory()
	{
		Template::Set('title', 'Edit Category');
		Template::Set('action', 'editcategory');
		Template::Set('category', DownloadData::GetAsset($this->get->id));
		
		Template::Show('downloads_categoryform.tpl');
	}
	
	public function editdownload()
	{
		Template::Set('title', 'Edit Download');
		Template::Set('action', 'editdownload');
		Template::Set('allcategories', DownloadData::GetAllCategories());
		Template::Set('download', DownloadData::GetAsset($this->get->id));
		
		Template::Show('downloads_downloadform.tpl');
	}
		
	protected function AddCategoryPost()
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
	
	protected function EditCategoryPost()
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
	
	protected function DeleteCategoryPost()
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
	
	protected function AddDownloadPost()
	{
		if($this->post->name == '' || $this->post->link == '')
		{
			Template::Set('message', 'Link and name must be entered');
			Template::Show('core_error.tpl');
			return;
		}
		
		$val = DownloadData::AddDownload($this->post->category, $this->post->name, $this->post->description,
					$this->post->link, $this->post->image);
		
		if($val == false)
		{
			Template::Set('message', DB::$error);
			Template::Show('core_error.tpl');
			return;
		}
	}
	
	protected function EditDownloadPost()
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
	
	protected function DeleteDownloadPost()
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