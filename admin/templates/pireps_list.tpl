<?php
if($title!='')
	echo "<h3>$title</h3>";
?>
<p><?php echo $descrip;?></p>
<?php
if(!$pireps)
{
	echo '<p>No reports have been found</p>';
	return;
}
?>
<p>There are a total of <?php echo count($pireps);?> flight reports in this category.</p>
<?php
if($paginate)
{
?>
<div style="float: right;">
	<a href="?admin=<?php echo $admin?>&start=<?php echo $start?>">Next Page</a>
	<br />
</div>
	<?php
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>PIREP Information</th>
	<th>Details</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($pireps as $report)
{
	$error = false; #IF there are any errors on the report, then don't allow accept
	
	if($report->accepted == PIREP_ACCEPTED)
		$class = 'success';
	else
		$class = 'error';
?>

<tr class="<?php echo $class?>">
	<td align="left" valign="top" width="10%" nowrap>
		<a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots?action=viewoptions&pilotid=<?php echo $report->pilotid;?>"><?php echo $report->firstname .' ' . $report->lastname?></a><br />
		<strong>Flight: <?php echo $report->code . $report->flightnum; ?></strong> - 
					<?php echo date(DATE_FORMAT, $report->submitdate); ?><br />
		Dep/Arr: <?php echo $report->depicao; ?>/<?php echo $report->arricao; ?><br />
		Flight Time: <?php echo $report->flighttime; ?><br />
		<strong>Current Status:	</strong>
			<?php 
			
			if($report->accepted == PIREP_ACCEPTED)
				echo 'Accepted';
			elseif($report->accepted == PIREP_REJECTED)
				echo 'Rejected';
			elseif($report->accepted == PIREP_PENDING)
				echo 'Approval Pending';
			elseif($report->accepted == PIREP_INPROGRESS)
				echo 'In Progress';
			
			?><br />
		<?php
		if($report->log != '')
		{
		?>
			<a id="dialog" class="jqModal"
				href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/viewlog?pirepid=<?php echo $report->pirepid;?>">View Log Details</a>
		<?php
		}
		?>
	</td>
	<td align="left" valign="top" >
		<span style="float: right">
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/viewcomments?pirepid=<?php echo $report->pirepid;?>">
				<img src="<?php echo SITE_URL?>/admin/lib/images/viewcomments.png" alt="View Comments" /></a>
		
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/addcomment?pirepid=<?php echo $report->pirepid;?>">
				<img src="<?php echo SITE_URL?>/admin/lib/images/addcomment.png" alt="Add Comment" /></a>
		</span>
		
		<strong>Aircraft: </strong>
		<?php 
			if($report->aircraft == '')
			{
				$error = true;
				echo '<span style="color: red">No aircraft! View log for more details</span>';
			}
			else
				echo $report->aircraft. " ($report->registration)";
		?><br />
		
		
		<strong>Load Count: </strong>
		<?php
			echo ($report->load!='')?$report->load:'-';
		?><br />
		
		
	<?php
		// Get the additional fields
		//	I know, badish place to put it, but it's pulled per-PIREP
		$fields = PIREPData::GetFieldData($report->pirepid);
		
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

	</td>
	<td align="left" width="1%" nowrap>
	
	<?php
		# If there was an error, don't allow the PIREP to go through
		if($error == false)
		{
	?>
		<a href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/<?php echo Vars::GET('page'); ?>" action="approvepirep"
			id="<?php echo $report->pirepid;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/accept.png" alt="Accept" /></a>
		<br />
	<?php
		}
	?>
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/rejectpirep?pirepid=<?php echo $report->pirepid;?>">
				<img src="<?php echo SITE_URL?>/admin/lib/images/reject.png" alt="Reject" /></a>
		<br />
		<a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/editpirep?pirepid=<?php echo $report->pirepid;?>">
			<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>
		<br />	
		<a href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/<?php echo Vars::GET('page'); ?>" action="deletepirep"
			id="<?php echo $report->pirepid;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a>
			
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<span style="float: right">* - double click to select</span><br />

<?php
if($paginate)
{
?>
<div style="float: right;">
	<a href="?admin=<?php echo $admin?>&start=<?php echo $start?>">Next Page</a>
	<br />
</div>
<?php
}
?>