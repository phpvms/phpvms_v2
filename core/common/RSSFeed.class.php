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
 * @package core_api
 */
class RSSFeed
{
	var $feed_contents;
	
	function RSSFeed($title='', $url='', $description = '')
	{
		
		$last_build_date = $this->LastBuildDate();
		
		$this->feed_contents .= '<title>' . $title . ' RSS Feed</title>'
							 . '<link>' . $url . '</link>'
							 . '<description>' . $description . '</description>'
							 . '<lastBuildDate>' . $last_build_date . '</lastBuildDate>'
							 . '<language>en-us</language>';				
	}
	
	/* Two ways to add to the main feed, overloaded depending on 
		what was passed
	*/
	function AddItem($title, $link, $guid='', $description)
	{
		$last_build_date = $this->LastBuildDate();
			
		if($guid == '')
		{
			$guid = $link . '#' . str_replace(' ', '', $title);
		}
		
		$this->feed_contents .= '<item>'
							 . '<title>'.$title .'</title>'
							 . '<link>'.$link.'</link>'
							 . '<guid>'.$guid.'</guid>'
							 . '<pubDate>'.$last_build_date.'</pubDate>'
							 . '<description>'.$description.'</description>'
							 . '</item>';		
	}
	
	function LastBuildDate()
	{
		return date('D, d M Y H:i:s T');
	}
	
	function BuildFeed($filepath)
	{
		$fp = fopen($filepath, 'w');
		if(!$fp) return false;
		
		$writestring = '<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel>';
		
		fwrite($fp, utf8_encode( $writestring), strlen($writestring));
		fwrite($fp, utf8_encode($this->feed_contents), strlen($this->feed_contents));
		
		$writestring = '</channel></rss>';
		
		fwrite($fp, utf8_encode($writestring), strlen($writestring));
		
		fclose($fp);
		
		return true;
	}
}
  
?>