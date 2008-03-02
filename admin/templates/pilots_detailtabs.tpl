<div id="tabcontainer">
	<ul>
		<li><a href="#pilotdetails"><span>Pilot Details</span></a></li>
		<li><a href="#customfields"><span>Custom Fields</span></a></li>
		<li><a href="#pireps"><span>View PIREPs</span></a></li>
		<li><a href="#resetpass"><span>Password Reset</span></a></li>
	</ul>
	<div id="pilotdetails">
	<?php //Template::Show('pilot_details.tpl'); ?>
		<h3><?php echo $pilotinfo->firstname . ' ' . $pilotinfo->lastname; ?></h3>

		<form action="action.php?admin=viewpilots" method="post">
		<dl> 
		<dt>Email Address</dt>
		<dd><input type="text" name="email" value="<?=$pilotinfo->email;?>" /></dd>

		<dt>Location</dt>
		<dd><input type="text" name="location" value="<?=$pilotinfo->location;?>"</dd>

		<dt>Last Login</dt>
		<dd><?php echo date(DATE_FORMAT, $pilotinfo->lastlogin);?></dd>

		<dt>Total Flights</dt>
		<dd><?=$pilotinfo->totalflights;?></dd>

		<dt>Total Hours</dt>
		<dd><?=$pilotinfo->totalhours;?></dd>
		</dl>
		</form>
	</div>
	<div id="customfields">
	Test
	</div>
	<div id="pireps">
	Pireps
	</div>
	<div id="resetpass">
	reset password
	</div>
</div>

<div id="messagebox">Settings were saved!</div>