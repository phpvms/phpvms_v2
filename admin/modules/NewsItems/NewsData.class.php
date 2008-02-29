<?php



class NewsData
{

	function GetAllNews()
	{
		return DB::get_results('SELECT id, subject, body, UNIX_TIMESTAMP(postdate) as postdate, postedby
									FROM ' . TABLE_PREFIX.'news ORDER BY postdate ASC');
	}
	
	function AddNewsItem($subject, $body)
	{
		$postedby = Auth::Username();
		
		$sql = 'INSERT INTO ' . TABLE_PREFIX . "news (subject, body, postdate, postedby)
					VALUES ('$subject', '$body', NOW(), '$postedby')";
					
		return DB::query($sql);		
	}
}