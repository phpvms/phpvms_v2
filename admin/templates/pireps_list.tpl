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
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>PIREP Information</th>
	<th>Details</th>
	<th>Options (* for double click)</th>
</tr>
</thead>
<tbody>
<?php
foreach($pireps as $report)
{
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
		<strong>Aircraft: </strong><?php echo $report->aircraft. " ($report->registration)" ?><br />
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
	</td>
	<td align="center" width="10%" nowrap>
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/viewcomments?pirepid=<?php echo $report->pirepid;?>">
				View Comments</a>
		<br />
		<a href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/editpirep?pirepid=<?php echo $report->pirepid;?>">
			<img src="<?php echo SITE_URL?>/admin/lib/images/edit.gif" alt="Edit" /></a>
		<br />	
		<a href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/<?php echo Vars::GET('page'); ?>" action="approvepirep"
			id="<?php echo $report->pirepid;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/accept.gif" alt="Accept" /></a>
		<br />
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/rejectpirep?pirepid=<?php echo $report->pirepid;?>">
				<img src="<?php echo SITE_URL?>/admin/lib/images/reject.gif" alt="Reject" /></a>
		<br />
		<a id="dialog" class="jqModal"
			href="<?php echo SITE_URL?>/admin/action.php/pirepadmin/addcomment?pirepid=<?php echo $report->pirepid;?>">
				<img src="<?php echo SITE_URL?>/admin/lib/images/addcomment.gif" alt="Add Comment" /></a>
			
	</td>
</tr>
<?php
}
?>
</tbody>
</table>

<?php
if($paginate)
{
?>
<a href="?admin=<?php echo $admin?>&start=<?php echo $start?>">Next Page</a></a>
<?php
}
?>