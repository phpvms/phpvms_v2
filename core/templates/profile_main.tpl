<div id="mainbox">
<h3>Pilot Center</h3>
<div class="indent">
<table>
<tr>
	<td><img src="<?php echo SITE_URL.AVATAR_PATH.'/'.$pilotcode.'.png';?>" /></td>
	<td valign="top">
			<p>
			Welcome back <?php echo $userinfo->firstname . ' ' . $userinfo->lastname; ?> 
					(<strong><?php echo $pilotcode;?></strong>)!
			<br />
			<?php
			if($report)
			{ ?>
				Your latest flight was <a href="<?php echo SITE_URL?>/index.php/pireps/view/<?php echo $report->pirepid?>"><?php echo $report->code . $report->flightnum; ?></a>
			<?php
			}
			if($nextrank)
			{
			?>
				<br />
				You have <?php echo ($nextrank->minhours-$userinfo->totalhours)?>
				hours left until your promotion to <?php echo $nextrank->rank?>
			<?php
			}
			?>
			<br />
			<strong>Your Rank: </strong><?php echo $userinfo->rank;?> <img src="<?php echo $userinfo->rankimage?>" /><br />
		</td>
	</tr>
	</table>
	<table>
	<tr>
	<td valign="top" nowrap>	
		<strong>Total Flights: </strong><?php echo $userinfo->totalflights?><br />
		<strong>Total Hours: </strong><?php echo $userinfo->totalhours?><br />
		<strong>Total Money: </strong>$<?php echo $userinfo->totalpay ?>
		<p>
		<strong>Profile Options</strong>
			<ul>
				<li><a href="<?php echo SITE_URL ?>/index.php/profile/editprofile">Edit My Profile</a></li>
				<li><a href="<?php echo SITE_URL ?>/index.php/profile/changepassword">Change my Password</a></li>
				<li><a href="<?php echo SITE_URL.SIGNATURE_PATH.'/'.$pilotcode.'.png' ?>">View my Badge</a></li>
			</ul>
		</p>
		<p>
			<strong>Flight Operations</strong>
			<ul>
				<li><a href="<?php echo SITE_URL?>/index.php/pireps/mine">View my PIREPs</a></li>
				<li><a href="<?php echo SITE_URL?>/index.php/pireps/routesmap">View a map of all my flights</a></li>
				<li><a href="<?php echo SITE_URL?>/index.php/pireps/filepirep">File a Pilot Report</a></li>
				<li><a href="<?php echo SITE_URL?>/index.php/Schedules/view">View Flight Schedules</a></li>
				<li><a href="<?php echo SITE_URL?>/index.php/Schedules/bids">View my flight bids</a></li>		
			</ul>	
		</p>
		<p><strong>ACARS Config</strong>
			<ul>
				<li><a href="<?php echo SITE_URL?>/action.php/acars/fsacarsconfig">Download FSACARS Config</a></li>
			</ul>
		</p>
	</td>
	<td valign="top">
		<?php StatsData::PilotAircraftFlownGraph($userinfo->pilotid); ?>
	</td>
	</tr></table>
</div>
</div>
<br />