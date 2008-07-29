<?php
if(!$allpages)
	return;

foreach($allpages as $page)
{
	echo '<li><a href="?page=content&p='.$page->filename.'">'.$page->pagename.'</a></li>';
}

?>