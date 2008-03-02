<h3><?php echo $pilotinfo->firstname . ' ' . $pilotinfo->lastname; ?></h3>

<form id="dialogform" action="action.php?admin=viewpilots" method="post">
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
<dt></dt>
<dd>
	<div id="results"></div>
	<input type="submit" name="submit" type="Save Changes" />
</dd>
</form>