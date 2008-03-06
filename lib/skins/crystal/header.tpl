<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"
 lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Virtual Airline</title>

<link rel="stylesheet" media="all" type="text/css" href="<?=SITE_URL?>/lib/skins/crystal/styles.css" />
<link rel="stylesheet" href="<?=SITE_URL?>/lib/skins/green/table/style.css" type="text/css" />

<?php
	Template::Show('core_javascript.tpl');
?>

<?php 
	echo $head_text;
?>

</head>
<body>
<div id="body">
<?php
	Template::Show('core_htmlreq.tpl');
?>
	<div id="topBanner">
	</div>
	
	<div id="topNav">
		<ul class="nav">
			<?php 
				Template::Show('core_navigation.tpl');
				echo $navigation_tree;
			?>
		</ul>
	</div>
	
	<div id="bodytext">