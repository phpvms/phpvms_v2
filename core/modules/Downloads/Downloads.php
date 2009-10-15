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
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ * @package module_frontpage
 */


class Downloads extends CodonModule
{
	
	public function index()
	{
		if(!Auth::LoggedIn())
		{
			echo 'You must be logged in to access this page!';
			return;
		}
		
		Template::Set('allcategories', DownloadData::GetAllCategories());
		Template::Show('downloads_list.tpl');
	}
	
	public function dl($id='')
	{
		$this->download($id);
	}
	
	public function download($id='')
	{
		if($id == '')
		{
			$this->index();
		}
		
		DownloadData::IncrementDLCount($id);
				
		Template::Set('download', DownloadData::GetAsset($id));
		Template::Show('download_item.tpl');
	}
}