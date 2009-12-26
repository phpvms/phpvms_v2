<tr class="<?php echo $class?> pirep_list" >
	<td align="left" valign="top" width="2%" nowrap style="padding-bottom: 15px; padding-top: 7px;">
		<img style="margin-right: 5px; margin-top: 5px;" height="25px" width="25px" src="<?php echo PilotData::GetPilotAvatar($pirep->pilotid);?>" align="left" />
	</td>
	<td align="left" valign="top" width="15%" nowrap>
		<div style="padding-bottom: 6px"><strong><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots?action=viewoptions&pilotid=<?php echo $pirep->pilotid;?>"><?php echo PilotData::GetPilotCode($pirep->code, $pirep->pilotid) . ' - ' .$pirep->firstname .' ' . $pirep->lastname;?></a></strong>
		<strong>Flight: <?php echo $pirep->code . $pirep->flightnum; ?></strong></div>
		<?php
		# If there was an error, don't allow the PIREP to go through
		if($error == false)
		{
		?>
		<a href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/<?php echo $load; ?>?pilotid=<?php echo $pirep->pilotid?>" action="approvepirep"
			id="<?php echo $pirep->pirepid;?>" class="pirepaction">
			<img src="<?php echo SITE_URL?>/admin/lib/images/accept.png" alt="Accept" /></a>

		<?php
	}
	?>
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/rejectpirep?pirepid=<?php echo $pirep->pirepid;?>&pilotid=<?php echo $pirep->pilotid; ?>">
				<img src="<?php echo SITE_URL?>/admin/lib/images/reject.png" alt="Reject" /></a>
		
		<a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/editpirep?pirepid=<?php echo $pirep->pirepid;?>&pilotid=<?php echo $pirep->pilotid?>">
			<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>
	
		<a href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/<?php echo $load; ?>?pilotid=<?php echo $pirep->pilotid?>" action="deletepirep"
			id="<?php echo $pirep->pirepid;?>" class="pirepaction">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a>
			
	</td>
	<td align="left" valign="top" colspan="3"  style="padding-bottom: 15px; padding-top: 7px;">
	<span style="float: right">
	<a id="dialog" class="jqModal"
		href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/viewcomments?pirepid=<?php echo $pirep->pirepid;?>">
			View Comments <span style="font-size: 12px; margin-top: -3px">(<?php echo PIREPData::getCommentCount($pirep->pirepid); ?>)</span></a> | <a id="dialog" class="jqModal"
		href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/addcomment?pirepid=<?php echo $pirep->pirepid;?>">
			Add Comment</a>
	</span>
	<strong>Dep/Arr: </strong><?php echo $pirep->depicao; ?>/<?php echo $pirep->arricao; ?> | 
	<strong>Flight Time: </strong><?php echo $pirep->flighttime; ?> 
	<strong>Current Status:	</strong>
	<?php 
	
	if($pirep->accepted == PIREP_ACCEPTED)
		echo 'Accepted';
	elseif($pirep->accepted == PIREP_REJECTED)
		echo 'Rejected';
	elseif($pirep->accepted == PIREP_PENDING)
		echo 'Approval Pending';
	elseif($pirep->accepted == PIREP_INPROGRESS)
		echo 'In Progress';
	
	?><br />
	<a href="#" onclick="$('#details_dialog_<?php echo $pirep->pirepid;?>').toggle()">View Details</a> | 
	<a id="dialog" class="jqModal"
		href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/viewlog?pirepid=<?php echo $pirep->pirepid;?>">View Log Details</a>

	<div id="details_dialog_<?php echo $pirep->pirepid;?>" style="display:none">
	<p style="margin-left: 7px">Click "Edit" to view additional details (finances, etc)</p>
	<table style="margin-top: 3px;">
	<tr>
	<td>
	<strong>Submit Date: </strong><?php echo date(DATE_FORMAT, $pirep->submitdate); ?> 
	</td><td>
	<strong>Aircraft: </strong>
		<?php 
		if($pirep->aircraft == '')
		{
			$error = true;
			echo '<span style="color: red">No aircraft! View log for more details</span>';
		}
		else
			echo $pirep->aircraft. " ($pirep->registration)";
		?>
	</td>
	</tr>
	
	<tr>
	<td>
		
		
	<strong>Load Count: </strong>
	<?php
	echo ($pirep->load!='')?$pirep->load:'-';
	?>
	
	</td>
	<td>
	
	<strong>Fuel Used: </strong>
	<?php
	echo ($pirep->fuelused!='')?$pirep->fuelused . Config::Get('LIQUID_UNIT_NAMES', Config::Get('LiquidUnit')):'-';
	?>
	
	</td>
	</tr>
	
	<tr>
	<td>
	
	<strong>Landing Rate: </strong>
	<?php
	echo $pirep->landingrate;
	?>
	
	</td>
	<td>
	
	<strong>Client: </strong>
	<?php
	echo $pirep->source;
	?>
	
	</td>
	</tr>
		
		
		
	<?php
	// Get the additional fields
	//	I know, badish place to put it, but it's pulled per-PIREP
	$fields = PIREPData::GetFieldData($pirep->pirepid);
	
	if(!$fields)
	{
		echo 'No additional data found';
	}
	else
	{
		$i=0;
		echo '<tr>';
		
		foreach ($fields as $field)
		{
			echo "<td><strong>{$field->title}:</strong> {$field->value}</td>";
		}
		
		if($i%2==1)
			echo '</tr><tr>';
		
		$i++;
	}
	
	echo '</tr>';
?>
	</table>
	<?php
	# If there was an error, don't allow the PIREP to go through
	if($error == true)
	{
		echo '<span style="color: red">There were errors with this PIREP. Correct them to be able to accept it</span>';
	}		
	?>
	</div>
	</td>
</tr>