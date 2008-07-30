<?php
if(!$allpages)
	return;

foreach($allpages as $page)
{
	echo '<li><a href="'.SITE_URL.'/index.php/Pages/'.$page->filename.'">'.$page->pagename.'</a></li>';
}

?>