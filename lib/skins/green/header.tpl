<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
<title>The PHP Virtual Airlines Management Project</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery-1.2.2.pack.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.form.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/suckerfish.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/phpvms.js"></script>

<link rel="stylesheet" href="<?=SITE_URL?>/lib/skins/green/style.css" type="text/css" />

<?php 
	echo $head_text;
?>
		

<script type="text/javascript">
$(document).ready(function() {
	$('#form').ajaxForm({
		target: '#bodytext',
    	success: function() {
          	$('#bodytext').fadeIn('slow');
    	}
    });
});
</script>
		
</head>
<body>

	
	<div id="body">
	<div id="innerwrapper">
		<div id="topNav">
			<img src="<?=SITE_URL?>/lib/skins/green/images/toplogo.jpg" alt="PHPVMS Logo" />
			<ul>
				<li><a href="http://code.google.com/p/phpvms/">Logout</a></li>
				<li><a href="http://groups.google.com/group/vamsys-discuss">Goto Home Page</a></li>
			</ul>
		</div>
		<div id="slice"></div>
		<div id="navbox">
				<ul class="nav">
					<?php 
						echo $navigation_tree;
					?>
				</ul>
		</div>
		
	
		<div id="bodytext">