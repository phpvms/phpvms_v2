<h3>Reports</h3>

<table width="100%">
	<tr>
		<td valign="top">
			<table id="tabledlist" class="tablesorter">
			<thead>
			<tr>
				<th><h3>VA Stats:</h3>At a Glance</th>
			</tr>
			</thead>
			<tbody>
				<tr>
				<td>
					<strong>Total Pilots: </strong><?php echo StatsData::PilotCount(); ?><br />
					<strong>Total Flights: </strong><?php echo StatsData::TotalFlights(); ?><br />
					<strong>Total Hours Flown: </strong><?php echo StatsData::TotalHours(); ?><br />
				</td>
				</tr>
			</tbody>
			</table>
		</td>
	</tr>
</table>

<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th colspan="2"><h3>Aircraft Usage</h3> Aircraft Hours and Usage</th>
</tr>
</thead>
<tbody>
	<tr>
	<td>
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
	
	<td width="5%"  nowrap>
		<?php echo StatsData::AircraftFlownGraph();?>
	</td>
	</tr>
</tbody>
</table>


<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th colspan="2"><h3>Routes</h3> Top 10 Routes</th>
</tr>
</thead>
<tbody>
	<tr>
	<td>
		<table id="tabledlist" class="tablesorter">
		<thead>
		<tr>
			<th>Route</th>
			<th>Times Flown</th>
			
		</tr>
		</thead>
		<tbody>
			<?php
			foreach($toproutes as $route)
			{
				?>
				<tr>
				<td><?php echo $route->code.$route->flightnum." ($route->depicao to $route->arricao)"?>
				</td>
				<td valign="top"><strong><?php echo $route->timesflown?></strong>
					<span class="flownchart" style="margin-top: 15px; margin-left: 4px;">
						<?php 
							$tot = $route->timesflown*10;
							echo ($tot+10).', '.$tot.','.($tot-2)?>
					</span>
				</td>
				</tr>
			<?php
			}?>
		</tbody>
		</table>
	</td>
	</tr>
</tbody>
</table>

<script type="text/javascript">
$(document).ready(function(){
	$('.flownchart').sparkline('html', { type:'bullet'});
});
</script>