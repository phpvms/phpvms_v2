<div id="mainbox">
<h3>Pilot Center</h3>
<div class="indent">
<p><strong>Welcome back <?php echo $userinfo->firstname . ' ' . $userinfo->lastname; ?>!</strong></p>
<table>
<tr>
	<td valign="top" align="center">
		<img src="<?php echo SITE_URL.AVATAR_PATH.'/'.$pilotcode.'.png';?>" />
		<br /><br />
		<img src="<?php echo RanksData::GetRankImage($userinfo->rank) ?>" />
	</td>
	<td valign="top">
		<ul style="margin-top: 0px;">
			<li><strong>Your Pilot ID: </strong> <?php echo $pilotcode; ?></li>
			<li><strong>Your Rank: </strong><?php echo $userinfo->rank;?></li>
			<?php
			if($report)
			{ ?>
				<li><strong>Latest Flight: </strong><a 
						href="<?php echo SITE_URL?>/index.php/pireps/view/<?php echo $report->pirepid?>">
						<?php echo $report->code . $report->flightnum; ?></a>
				</li>
			<?php
			}
			?>
			<li><strong>Total Flights: </strong><?php echo $userinfo->totalflights?></li>
			<li><strong>Total Hours: </strong><?php echo $userinfo->totalhours?></li>
			<li><strong>Total Money: </strong><?php echo FinanceData::FormatMoney($userinfo->totalpay) ?></li>
		
			<?php
			if($nextrank)
			{
			?>
				<p>You have <?php echo ($nextrank->minhours-$userinfo->totalhours)?> hours 
					left until your promotion to <?php echo $nextrank->rank?></p>
			<?php
			}
			?>
		</ul>

	</td>
</tr>
</table>
	<table>
	<tr>
	<td valign="top" nowrap>
		<p>
			<strong>Profile Options</strong>
			<ul>
				<li><a href="<?php echo SITE_URL ?>/index.php/profile/editprofile">Edit My Profile, Email and Avatar</a></li>
				<li><a href="<?php echo SITE_URL ?>/index.php/profile/changepassword">Change my Password</a></li>
				<li><a href="<?php echo SITE_URL.SIGNATURE_PATH.'/'.$pilotcode.'.png' ?>">View my Badge</a></li>
				<li><a href="<?php echo SITE_URL ?>/index.php/downloads">View Downloads</a></li>
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
				<li><a href="<?php echo SITE_URL?>/index.php/Finances">View VA Finances</a></li>
			</ul>	
		</p>
		<p>
			<strong>ACARS Config</strong>
			<ul>
				<li><a href="<?php echo SITE_URL?>/action.php/acars/fsacarsconfig">Download FSACARS Config</a></li>
				<li><a href="<?php echo SITE_URL?>/action.php/acars/fspaxconfig">Download FSPax Config</li>
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