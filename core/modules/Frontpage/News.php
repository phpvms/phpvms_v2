<?php


class News
{
	// This function gets called directly in the template
	function ShowNewsFront()
	{
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX .'news ORDER BY postdate ASC LIMIT 5';
		
		$res = DB::get_results($sql);
		
		foreach($res as $row)
		{
			Template::Set('subject', $row->subject);
		
			Template::Show('news_newsitem.tpl');
		}
	}
}