<h3><?php echo $pilotinfo->firstname . ' ' . $pilotinfo->lastname; ?></h3>
<dl> 
<dt>Email Address</dt>
<dd><?=$pilotinfo->email;?></dd>

<dt>Location</dt>
<dd><?=$pilotinfo->location;?></dd>

<dt>Last Login</dt>
<dd><?php echo date(DATE_FORMAT, $pilotinfo->lastlogin);?></dd>

<dt>Total Flights</dt>
<dd><?=$pilotinfo->totalflights;?></dd>

<dt>Total Hours</dt>
<dd><?=$pilotinfo->totalhours;?></dd>
</dl>