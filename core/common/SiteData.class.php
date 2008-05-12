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
 * @package phpvms
 * @subpackage news_and_pages
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
		$postedby = Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname;
		
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
			
		return self::EditPageFile($filename, $content);
	}
	
	function GetPageContent($filename)
	{
		// Round-about way, I know. But it's in the name of security. If they're giving a
		//	bogus name, then it won't find it. 
		
		$sql = 'SELECT pagename, filename FROM '.TABLE_PREFIX.'pages WHERE filename=\''.$filename.'\'';
		$row = DB::get_row($sql);
	
		if(!$row) return;
		
		//run output buffering, so we can parse any PHP in there
		
		ob_start();
		include PAGES_PATH . '/' . $row->filename . PAGE_EXT; 
		$row->content = ob_get_contents();
		ob_end_clean();
		
		return $row;
	}
	
	function EditFile($pageid, $content)
	{
		$pagedata = SiteData::GetPageData($pageid);
		
		if(self::EditPageFile($pagedata->filename, stripslashes($content)))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	function EditPageFile($filename, $content)
	{
		//create the file
		$filename = PAGES_PATH . '/' . $filename . PAGE_EXT;
		$fp = fopen($filename, 'w');
		
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