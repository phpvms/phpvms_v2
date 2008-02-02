<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
<title>The PHP Virtual Airlines Management Project</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		
<script type="text/javascript" src="lib/js/jquery-1.2.2.pack.js"></script>
<script type="text/javascript" src="lib/js/suckerfish.js"></script>
<script type="text/javascript" src="lib/js/phpvms.js"></script>

<link rel="stylesheet" href="lib/skins/default/style.css" type="text/css" />

<?php
	// Print our additional header text from modules
	echo $head_text;
?>

</head>
<body>

	<div id="topNav">
		<ul>
			<li><a href="http://wiki.phpvms.net">Log In</a></li>
			<li><a href="http://groups.google.com/group/vamsys-discuss">Admin Panel</a></li>
		</ul>
	</div>
	
	<div id="body">
	
		<div id="nav">
			<ul class="nav">
				<?php 
				// Print out our navigation tree from the modules
				echo $navigation_tree; ?>
				<li><a href="http://groups.google.com/group/vamsys-discuss">Admin Panel</a>
					<ul>
						<li><a href="http://groups.google.com/group/vamsys-discuss">Admin Panel</a></li>
						<li><a href="http://wiki.phpvms.net">Log In</a></li>
					</ul>
				</li>
				<li><a href="http://wiki.phpvms.net">Log In</a></li>
			</ul>
		</div>
	
		<div id="mainbody">