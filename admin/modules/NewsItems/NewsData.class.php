<?php



class NewsData
{

	function AddNewsItem($subject, $body)
	{
		$postedby = Auth::Username();
		
		$sql = 'INSERT INTO ' . TABLE_PREFIX . "news (subject, body, postdate, postedby)
					VALUES ('$subject', '$body', NOW(), '$postedby')";
					
		return DB::query($sql);		
	}

}