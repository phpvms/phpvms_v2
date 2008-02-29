<?php


class News
{
	// This function gets called directly in the template
	function ShowNewsFront()
	{
		
		$sql = 'SELECT id, subject, body, postedby, UNIX_TIMESTAMP(postdate) AS postdate
				 FROM ' . TABLE_PREFIX .'news ORDER BY postdate DESC LIMIT 5';
		
		$res = DB::get_results($sql);
		
		if(!$res)
			return;
			
		foreach($res as $row)
		{
			//TODO: change the date format to a setting in panel
			Template::Set('subject', $row->subject);
			Template::Set('body', $row->body);
			Template::Set('postedby', $row->postedby);
			Template::Set('postdate', date('m/d/Y', $row->postdate));
		
			Template::Show('news_newsitem.tpl');
		}
	}
}