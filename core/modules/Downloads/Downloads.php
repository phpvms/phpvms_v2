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
	
	public function Controller()
	{
		if(!Auth::LoggedIn())
		{
			echo 'You must be logged in to access this page!';
			break;
		}
		
		switch($this->get->id)
		{
			case '':				
			
				Template::Set('allcategories', DownloadData::GetAllCategories());
				Template::Show('downloads_list.tpl');
				break;	
				
			default:
			
				# Download the item, but "one up" the download count
				
				DownloadData::IncrementDLCount($this->get->id);
				
				Template::Set('download', DownloadData::GetAsset($this->get->id));
				Template::Show('download_item.tpl');
				
				break;	
		}
		
		# Retrieve our download ID and download it
		if($this->get->id != '')
		{
						
		}
	}
	
}