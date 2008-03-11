<?php
if(!$allpages)
	return;

foreach($allpages as $page)
{
	echo '<li><a href="?page=content&p='.$page->pagename.'">'.$page->filename.'</a></li>';
}

?>