<?php
/**
 * SiteData
 *
 * Model for site CMS related Data
 * 
 * @author Nabeel Shahzad <contact@phpvms.net>
 * @copyright Copyright (c) 2008, phpVMS Project
 * @license http://www.phpvms.net/license.php
 * 
 * @package SiteData
 */

class SiteData
{

	function GetAllNews()
	{
		return DB::get_results('SELECT id, subject, body, UNIX_TIMESTAMP(postdate) as postdate, postedby
									FROM ' . TABLE_PREFIX.'news ORDER BY postdate DESC');
	}
	
	function AddNewsItem($subject, $body)
	{
		$postedby = Auth::Username();
		
		$sql = 'INSERT INTO ' . TABLE_PREFIX . "news (subject, body, postdate, postedby)
					VALUES ('$subject', '$body', NOW(), '$postedby')";
					
		return DB::query($sql);		
	}
	
	function DeleteItem($id)
	{
		$sql = 'DELETE FROM ' . TABLE_PREFIX . 'news WHERE id='.$id;
		
		return DB::query($sql);
	}
	
	function GetAllPages($onlyenabled=false)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."pages";
		
		if($onlyenabled == true)
		{
			$sql .= ' WHERE enabled=1';
		}
		
		return DB::get_results($sql);
	}
	
	function GetPageData($pageid)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pages WHERE pageid='.$pageid;
		return DB::get_row($sql);
	}
	
	function AddPage($title, $content)
	{
		$filename = strtolower($title);
	
		//TODO: replace this with a regex
		$filename = str_replace(' ', '', $filename);
		$filename = str_replace('?', '', $filename);
		$filename = str_replace('!', '', $filename);	
		$filename = str_replace('@', '', $filename);
		$filename = str_replace('.', '', $filename);
		$filename = str_replace(',', '', $filename);
		$filename = str_replace('\'', '', $filename);
				
		$filename = str_replace('+', 'and', $filename);
		$filename = str_replace('&', 'and', $filename);
	
		//take out any slashes
		$filename = preg_replace('/(\/|\\\)++/', '', $filename);
		
		//$filename .= '.html';
		$postedby = Auth::Username();
		
		$sql = "INSERT INTO ".TABLE_PREFIX."pages (pagename, filename, postedby, postdate)
					VALUES ('$title', '$filename', '$postedby', NOW())";
					
		$ret = DB::query($sql);
		if(!$ret)
			return false;
			
		return self::EditPage($filename, $content);
	}
	
	function GetPageContent($filename)
	{
		// Round-about way, I know. But it's in the name of security. If they're giving a
		//	bogus name, then it won't find it. 
		
		$sql = 'SELECT filename FROM '.TABLE_PREFIX.'pages WHERE filename=\''.$filename.'\'';
		$row = DB::get_row($sql);
	
		if(!$row) return;
		include PAGES_PATH . '/' . $row->filename . '.html';
	}
	
	function EditPage($filename, $content)
	{
		//create the file
		$filename = PAGES_PATH . '/' . $filename . '.html';
		$fp = @fopen($filename, 'w');
		
		if(!$fp) 
		{
			return false;
		}
		else 
		{
			fwrite($fp, $content, strlen($content));
			fclose($fp);
			return true;
		}
	}
}