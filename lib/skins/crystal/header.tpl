<?php
/**
 * 
 * STOP!!!!!!!!
 * 
 * 
 * 
 * 
 * Are you editing the crystal skin directly?
 * DON'T
 * 
 * Copy and rename the crystal folder. Otherwise it'll get 
 * overwritten in an update.
 * 
 * Also, READ THE DOCS
 * 
 *   http://www.phpvms.net/docs/skinning
 * 
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>

<link rel="stylesheet" media="all" type="text/css" href="<?php echo SITE_URL?>/lib/skins/crystal/styles.css" />

<?php
	Template::Show('core_htmlhead.tpl');
?>

</head>
<body>
<div id="body">
<div id="innerwrapper">
<?php
	Template::Show('core_htmlreq.tpl');
?>
	<div id="topBanner">
	</div>
	
	<div id="topNav">
		<ul class="nav">
			<?php
				Template::Show('core_navigation.tpl');
			?>
		</ul>
	</div>
	
	<div id="bodytext">