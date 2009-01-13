<table width="100%">
<tr>
<td colspan="2">
	<p>Welcome to the phpVMS installer! In order for phpVMS to work properly, you need to have a few prerequisites. Mainly, you must be running PHP 5. The installer has found a few problems with your install, which are highlighted below.
</td>
</tr>
<tr>
	<td><strong>PHP Version (5 required) </strong></td>
	<td><?php echo $phpversion;?></td>
</tr>

<tr>
	<td><strong>Site Configuration File </strong></td>
	<td><?php echo $configfile;?></td>
</tr>

<tr>
	<td valign="top"><strong>Directories and Files: </strong></td>
	<td><?php echo $directories;?></td>
</tr>

<tr>
	<td colspan="2" align="center"><p>Once you correct these, refresh this page to start the installer.</p></td>
</tr>
</table>