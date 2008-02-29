<?php


include dirname(__FILE__) . '/NewsData.class.php';


class NewsItems
{
	
	function NavBar()
	{
		echo '<li><a href="#">News</a>
					<ul>
						<li><a href="?admin=viewnews">View News</a></li>
						<li><a href="?admin=addnews">Add News</a></li>
					</ul>
				</li>';
	}
	
	function Controller()
	{
		
		if(isset($_POST['addnews']))
		{
			$this->AddNewsItem();
		}
		
		switch(Vars::GET('admin'))
		{
			case 'viewnews':
				$this->ViewNews();
				$this->AddNewsForm();
				break;
			case 'addnews':
				$this->AddNewsForm();
				break;
		}
	}
	
	function ViewNews()
	{
		
	}

	function AddNewsForm()
	{
		Template::Show('news_additem.tpl');
	}	
	
	function AddNewsItem()
	{
		$subject = Vars::POST('subject');
		$body = Vars::POST('body');
		
		if($subject == '')
			return;
		
		if($body == '')
			return;
			
		if(!NewsData::AddNewsItem($subject, $body))
		{
			echo 'fail';
		}
		
	}
}

?>