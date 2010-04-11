<h3>Pilots List</h3>
<div align="center">
	Find by Last Name: <a href="?admin=viewpilots&letter=">View All</a>
<?php
for($i=65;$i<91;$i++)
{
	echo '<a href="'.SITE_URL.'/admin/index.php/pilotadmin/viewpilots?letter='.chr($i).'">'.chr($i).'</a> ';
}
?>
</div>
<br />
<?php
if(!$allpilots)
{
	echo '<p>There are no pilots!</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th width="1%">Pilot ID</th>
	<th>Pilot Name</th>
	<th>Email</th>
	<th>Location</th>
	<th>Status</th>
	<th nowrap="nowrap" align="center">Rank</th>
	<th nowrap="">Total Flights</th>
	<th nowrap="">Total Hours</th>
	<th>Last IP</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allpilots as $pilot)
{
?>
<tr>
	<td nowrap><?php echo PilotData::GetPilotCode($pilot->code, $pilot->pilotid);?> </td>
	<td width="1%" nowrap><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots?action=viewoptions&pilotid=<?php echo $pilot->pilotid;?>"><?php echo $pilot->firstname . ' ' . $pilot->lastname; ?></a></td>
	<td align="left"><?php echo $pilot->email; ?></td>
	<td align="center" width="1%"><img src="<?php echo Countries::getCountryImage($pilot->location);?>" </td>
	<td align="center" width="1%"><?php echo ($pilot->retired==0) ? 'Active' : 'Retired'; ?></td>
	<td align="center" width="1%" nowrap=""><?php echo $pilot->rank; ?></td>
	<td align="center" width="1%"><?php echo $pilot->totalflights; ?></td>
	<td align="center" width="1%"><?php echo $pilot->totalhours; ?></td>
	<td align="center" width="1%"><?php echo $pilot->lastip; ?></td>
	<td align="center" width="1%" nowrap>
		<button class="{button:{icons:{primary:'ui-icon-wrench'}}}"
			onclick="window.location='<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots?action=viewoptions&pilotid=<?php echo $pilot->pilotid;?>';">
			Edit</button></td>
</tr>
<?php
}
?>
</tbody>
</table>