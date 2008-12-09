<h3>Reports</h3>

<table width="100%">
	<tr>
		<td valign="top">
			<h3>VA Stats:</h3>
			<strong>Total Pilots: </strong><?php echo StatsData::PilotCount(); ?><br />
			<strong>Total Flights: </strong><?php echo StatsData::TotalFlights(); ?><br />
			<strong>Total Hours Flown: </strong><?php echo StatsData::TotalHours(); ?><br />
		</td>
	</tr>
</table>


<table width="100%">
	<tr>
		<td valign="top">
			<h3>Aircraft Usage</h3>
			<table id="tabledlist" class="tablesorter">
			<thead>
			<tr>
				<th>Aircraft</th>
				<th>Hours</th>
				<th>Miles</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach($acstats as $stat)
			{
			?>
				<tr>
					<td><?php echo "$stat->aircraft ($stat->registration)"?></td>
					<td><?php echo $stat->hours?></td>
					<td><?php echo $stat->distance?></td>
				</tr>
						
			<?php	
			}?>
			</tbody>
			</table>
		</td>
		<td>
			<?php echo StatsData::AircraftFlownGraph();?>
		</td>
	</tr>
</table>