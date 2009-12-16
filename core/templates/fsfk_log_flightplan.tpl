<div class="fsfk_section_title" style="font-weight:bold">Flight Details</div>
<table class="fsfk_flightplan">
<thead>
<tr>
	<td>#</td>
	<td>Name</td>
	<td>Type</td>
	<td>Time</td>
	<td>Fuel (lbs)</td>
	<td>IAS (kts)</td>
	<td>Altitude (ft)</td>
	<td>Heading</td>
	<td>Wind</td>
	<td>OAT</td>
</tr>
</thead>
<tbody>

<?php 

foreach($lines as $point)
{
	// Data about each point is separated by a |
	$point = explode('|', $point);
	
	echo '<tr>';
	foreach($point as $info)
	{
		echo '<td>'.$info.'</td>';
	}
	echo '</tr>';
}
?>

</tbody>
</table>
<br />