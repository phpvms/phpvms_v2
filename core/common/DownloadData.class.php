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

class DownloadData
{
	
	public static function GetAllCategories()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'downloads
					WHERE pid=0';
		
		return DB::get_results($sql);
	}
	
	public static function GetAsset($id)
	{
		$id = DB::escape($id);
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'downloads
					WHERE id='.$id;
		
		return DB::get_row($sql);
	}
	
	public static function FindCategory($categoryname)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'downloads
					WHERE name=\''.$categoryname.'\' AND pid=0';
		
		return DB::get_row($sql);
	}

	public static function GetDownloads($categoryid)
	{
		if($categoryid == '')	return false;
		
		$categoryid = intval($categoryid);
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'downloads
					WHERE pid='.$categoryid;
		
		return DB::get_results($sql);
	}
	
	public static function GetAllDownloads()
	{
		
	}
	
	public static function AddCategory($name, $link='', $image='')
	{
		if($name == '') return false;
		
		$sql = 	"INSERT INTO ".TABLE_PREFIX."downloads
					(pid, name, link, image)
				VALUES	(0, '$name', '$link', '$image')";
				
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
	
	public static function RemoveCategory($id)
	{
		if($id == '') return false;
		$id = intval($id);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'downloads
					WHERE pid='.$id;
					
		DB::query($sql);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'downloads
					WHERE id='.$id;
					
		DB::query($sql);
	}
	public static function AddDownload($categoryid, $name, $link, $image)
	{
		if($categoryid == '') return false;
		
		$name = DB::escape($name);
		
		$sql = 	"INSERT INTO ".TABLE_PREFIX."downloads
							(pid, name, link, image)
					VALUES	($categoryid, '$name', '$link', '$image')";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
	
	public static function EditAsset($id, $name, $link='', $image='')
	{
		if($id == '' || $name == '') return false;
		
		$id = intval($id);
		
		$sql = "UPDATE ".TABLE_PREFIX."downloads
					SET name='$name', link='$link', image='$image'
					WHERE id=$id";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
	
	public static function RemoveAsset($id)
	{
		if($id == '') return false;
		
		$id = intval($id);
		
		$sql = "DELETE FROM ".TABLE_PREFIX."downloads
					WHERE id=$id";
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
}
