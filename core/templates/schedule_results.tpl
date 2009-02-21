<?php
if(!$allroutes)
{
	echo '<p align="center">No routes have been found!</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Info</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allroutes as $route)
{
?>
<tr>
	<td nowrap>
		<a href="<?php echo SITE_URL?>/index.php/schedules/details/<?php echo $route->id?>"><?php echo $route->code . $route->flightnum?><?php echo '('.$route->depicao.' - '.$route->arricao.')'?></a>
		<br />
		
		<strong>Departure: </strong><?php echo $route->deptime;?> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Arrival: </strong><?php echo $route->arrtime;?><br />
		<strong>Equipment: </strong><?php echo $route->aircraft; ?> (<?php echo $route->registration;?>)  <strong>Distance: </strong><?php echo $route->distance . Config::Get('UNITS');?>
		<br />
		<?php echo ($route->route=='')?'':'<strong>Route: </strong>'.$route->route.'<br />' ?>
		<?php echo ($route->notes=='')?'':'<strong>Notes: </strong>'.$route->notes.'<br />' ?>
	</td>
	<td nowrap>
		<a href="<?php echo SITE_URL?>/index.php/schedules/details/<?php echo $route->id?>">View Details</a><br />
		<?php if (Auth::LoggedIn())
		{ ?>
		<a id="<?php echo $route->id; ?>" class="addbid" href="<?php echo SITE_URL?>/action.php/Schedules/addbid/">Add to Bid</a>
		<?php
		} ?>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>