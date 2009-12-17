<?php 

$error = false; #IF there are any errors on the report, then don't allow accept
?>
<tr class="<?php echo $class?> pirep_list">
	<td align="left" valign="top" width="2%" nowrap>
		<img style="margin-right: 5px; margin-top: 5px;" height="50px" width="50px" src="<?php echo PilotData::GetPilotAvatar($pirep->pilotid);?>" align="left" />
	</td>
	<td align="left" valign="top" width="15%" nowrap>
		<strong><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots?action=viewoptions&pilotid=<?php echo $pirep->pilotid;?>"><?php echo $pirep->firstname .' ' . $pirep->lastname?></a></strong><br />
		<strong>Flight: <?php echo $pirep->code . $pirep->flightnum; ?></strong> - <br />
		Dep/Arr: <?php echo $pirep->depicao; ?>/<?php echo $pirep->arricao; ?><br />
		Flight Time: <?php echo $pirep->flighttime; ?><br />
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
		<?php
		if($pirep->log != '')
		{
		?>
			<a id="dialog" class="jqModal"
				href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/viewlog?pirepid=<?php echo $pirep->pirepid;?>">View Log Details</a>
		<?php
	}
	?>
	</td>
	<td align="left" valign="top" >
		<span style="float: right">
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/viewcomments?pirepid=<?php echo $pirep->pirepid;?>">
				View Comments <span style="font-size: 12px; margin-top: -3px">(<?php echo PIREPData::getCommentCount($pirep->pirepid); ?>)</span></a> | <a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/addcomment?pirepid=<?php echo $pirep->pirepid;?>">
				Add Comment</a>
		</span>
		
		<div id="details_dialog" >
		<strong>Submit Date: </strong><?php echo date(DATE_FORMAT, $pirep->submitdate); ?><br />
		<strong>Aircraft: </strong>
				<?php 
		if($pirep->aircraft == '')
		{
			$error = true;
			echo '<span style="color: red">No aircraft! View log for more details</span>';
		}
		else
			echo $pirep->aircraft. " ($pirep->registration)";
		?><br />
		
		
		<strong>Load Count: </strong>
		<?php
		echo ($pirep->load!='')?$pirep->load:'-';
		?><br />
		
		<strong>Fuel Used: </strong>
		<?php
		echo ($pirep->fuelused!='')?$pirep->fuelused . Config::Get('LIQUID_UNIT_NAMES', Config::Get('LiquidUnit')):'-';
		?><br />
		
		<strong>Landing Rate: </strong>
		<?php
		echo $pirep->landingrate;
		?><br />
		<strong>Client: </strong>
		<?php
		echo $pirep->source;
		?><br />
		
		
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
		foreach ($fields as $field)
		{
	?>		<strong><?php echo $field->title ?>:</strong> <?php echo $field->value ?><br />
		<?php
	}
}
?>
		
		<?php
		# If there was an error, don't allow the PIREP to go through
		if($error == true)
		{
			echo '<span style="color: red">There were errors with this PIREP. Correct them to be able to accept it</span>';
		}		
		?>
		</div>

	</td>
	<td align="left" width="1%" nowrap>
	
	<?php
	# If there was an error, don't allow the PIREP to go through
	if($error == false)
	{
	?>
		<a href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/<?php echo $load; ?>?pilotid=<?php echo $pirep->pilotid?>" action="approvepirep"
			id="<?php echo $pirep->pirepid;?>" class="pirepaction">
			<img src="<?php echo SITE_URL?>/admin/lib/images/accept.png" alt="Accept" /></a>
		<br />
	<?php
}
?>
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/rejectpirep?pirepid=<?php echo $pirep->pirepid;?>&pilotid=<?php echo $pirep->pilotid; ?>">
				<img src="<?php echo SITE_URL?>/admin/lib/images/reject.png" alt="Reject" /></a>
		<br />
		<a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/editpirep?pirepid=<?php echo $pirep->pirepid;?>&pilotid=<?php echo $pirep->pilotid?>">
			<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>
		<br />	
		<a href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/<?php echo $load; ?>?pilotid=<?php echo $pirep->pilotid?>" action="deletepirep"
			id="<?php echo $pirep->pirepid;?>" class="pirepaction">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a>
			
	</td>
</tr>