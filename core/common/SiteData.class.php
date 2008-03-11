<?php



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
	
	function GetAllPages()
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."pages";
		
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
		
		$filename .= '.html';
		$postedby = Auth::Username();
		
		$sql = "INSERT INTO ".TABLE_PREFIX."pages (pagename, filename, postedby, postdate)
					VALUES ('$title', '$filename', '$postedby', NOW())";
					
		$ret = DB::query($sql);
		if(!$ret)
			return false;
			
		return self::EditPage($filename, $content);
	}
	
	function EditPage($filename, $content)
	{
		//create the file
		$filename = PAGES_PATH . '/' . $filename;
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