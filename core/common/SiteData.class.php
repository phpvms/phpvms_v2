<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 *  This program is free software; you can redistribute it and/or modify it 
 *  under the terms of the GNU General Public License as published by the Free 
 *	Software Foundation; either version 2 of the License, or (at your option) 
 *	any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT 
 *  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
 *	FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along with 
 *	this program; if not, write to the:
 *		Free Software Foundation, Inc., 
 *		59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license GNU Public License http://opensource.org/licenses/gpl-license.php
 * @package core_api
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