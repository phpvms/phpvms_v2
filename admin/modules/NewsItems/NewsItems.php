<?php

class NewsItems
{
	
	function NavBar()
	{
		echo '<li><a href="#">News Articles</a>
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
				break;
			case 'addnews':
				$this->AddNews();
				break;
		}
	}
	
	function ViewNews()
	{
		
	}

	function AddNews()
	{
		Template::Show('news_additem.tpl');
	}	
	
	function AddNewsItem()
	{
		
	}
}

?>