<?php
/**
 * 
 * STOP!!!!!!!!
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
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title><?php echo $page_title; ?></title>

<link rel="stylesheet" media="all" type="text/css" href="<?php echo SITE_URL?>/lib/skins/crystal/styles.css" />

<?php 
/* This is required, so phpVMS can output the necessary libraries it needs */
echo $page_htmlhead; 
?>

<!-- Any custom Javascript should be placed here, after the above Template::Show() call -->

</head>
<body>
<?php
/* This should be the first thing you place after a <body> tag
	This is also required by phpVMS */
echo $page_htmlreq;
?>
<div id="body">
<div id="innerwrapper">
	<div id="topBanner">
		<div id="topLogin">
		<?php 
		/* 
		Quick example of how to see if they're logged in or not
		Only show this login form if they're logged in */
		if(Auth::LoggedIn() == false)
		{ ?>
		<form name="loginform" action="<?php echo url('/login'); ?>" method="post">
			Sign-in with your pilot id or email, or <a href="<?php echo url('/registration'); ?>">register</a><br />
		<input type="text" name="email" value="" onClick="this.value=''" />
		<input type="password" name="password" value="" />
		<input type="hidden" name="remember" value="on" />
		<input type="hidden" name="redir" value="index.php/profile" />
		<input type="hidden" name="action" value="login" />
		<input type="submit" name="submit" value="Log In" />
		</form>
		<?php
	}	
	/* End the Auth::LoggedIn() if */
	else /* else - they're logged in, so show some info about the pilot, and a few links */
	{
		
		/* Auth::$userinfo has the information about the user currently logged in */
		
		/* We will use this, this gets their full pilot id, formatted properly */
		$pilotid = PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid);
	?>
<img align="left" height="50px" width="50px" style="margin-right: 10px;"
				src="<?php echo PilotData::getPilotAvatar($pilotid);?>" />

			<strong>Pilot ID: </strong> <?php echo $pilotid ; ?>
			<strong>Rank: </strong><?php echo Auth::$userinfo->rank;?><br />
			<strong>Total Flights: </strong><?php echo Auth::$userinfo->totalflights?>, <strong>Total Hours: </strong><?php echo Auth::$userinfo->totalhours;?>				<br />
			
			<a href="<?php echo url('/pireps/new');?>">File a New PIREP</a> | 
			<a href="<?php echo url('/schedules/bids');?>">View My Bids</a> | 
			<a href="<?php echo url('/profile/');?>">View Pilot Center</a>
		<?php
	} /* End the else */
	?>
		</div>
	</div>
	
	<div id="topNav">
		<ul class="nav">
			<?php
			Template::Show('core_navigation.tpl');
			?>
		</ul>
	</div>
	
	<div id="bodytext">
	
	<?php
	echo $page_content;
	?>
	
	</div>
	</div>
	<div id="footer">
	<p>copyright &copy; 2007 - <?php echo date('Y') ?><br />
	<!-- Please retain this!! It's part of the phpVMS license. You must display a
			"powered by phpVMS" somewhere on your page. Thanks! -->
	<a href="http://www.phpvms.net" target="_blank">powered by phpVMS</a></p>
	</div>	
</div>
</body>
</html>