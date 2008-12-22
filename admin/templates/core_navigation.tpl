<li><a href="<?php echo SITE_URL?>/admin/index.php/dashboard">Dashboard</a></li>
<li><a href="#">Site</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/sitecms/viewnews">Site News</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/sitecms/viewpages">Site Pages</a></li>
	</ul>
</li>
<li><a href="?admin=airlines">Operations</a>
	 <ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/airlines">1. Airlines</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/aircraft">2. Aircraft</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/airports">3. Airports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/schedules">4. Flight Schedules</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/import">Import Schedules</a></li>
	</ul>
</li>
<li><a href="#">PIREPs</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewpending">View Pending</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewrecent">View Recent Reports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewall">View All Reports</a></li>
	</ul>
</li>
<li><a href="#">Pilots</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/pendingpilots">Pending Registrations</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots">View Registered Pilots</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/pilotgroups">Pilot Groups</a></li>
	</ul>
</li>
<li><a href="?admin=reports">Reports</a></li>
<li><a href="#">Settings</a>
	<ul>
		<li><a href="?admin=settings">General Settings</a></li>
		<li><a href="?admin=customfields">Profile Fields</a></li>
		<li><a href="?admin=pirepfields">PIREP Fields</a></li>
		<li><a href="?admin=pilotranks">Pilot Ranks</a></li>
		<li><a href="?admin=about">About phpVMS</a></li>
	</ul>
</li>
<?php echo $MODULE_NAV_INC;?>
<li><a href="<?php echo SITE_URL?>/index.php/Login/logout">Log Out</a></li>
<li><a href="<?php echo SITE_URL?>/index.php">Goto Site</a></li>