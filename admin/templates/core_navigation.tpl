<li>
	<a class="menu" href="<?php echo SITE_URL?>/admin/index.php/dashboard">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/dashboard_icon.png" />Dashboard
	</a>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/site_icon.png" />Site
	</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/sitecms/viewnews">Site News</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/sitecms/viewpages">Site Pages</a></li>
	</ul>
</li>
<li><a class="menu" href="?admin=airlines">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/operations_icon.png" />Operations
	</a>
	 <ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/airlines">1. Airlines</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/aircraft">2. Aircraft</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/airports">3. Airports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/schedules">4. Flight Schedules</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/import">Import Schedules</a></li>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/pireps_icon.png" />PIREPs</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewpending">View Pending</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewrecent">View Recent Reports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewall">View All Reports</a></li>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/pilots_icon.png" />Pilots</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/pendingpilots">Pending Registrations</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots">View Registered Pilots</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/pilotgroups">Pilot Groups</a></li>
	</ul>
</li>
<li><a class="menu" href="<?php echo SITE_URL?>/admin/index.php/reports">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/reports_icon.png" />Reports</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/reports">Overview</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/reports/financials">Financial Reports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/reports/aircraft">Aircraft Reports</a></li>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/settings_icon.gif" />Settings</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings">General Settings</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings/customfields">Profile Fields</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings/pirepfields">PIREP Fields</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotranking/pilotranks">Pilot Ranks</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings/about">About phpVMS</a></li>
	</ul>
</li>
<?php echo $MODULE_NAV_INC;?>
<li><a class="menu" href="<?php echo SITE_URL?>/index.php/Login/logout">Log Out</a></li>
<li><a class="menu" href="<?php echo SITE_URL?>/index.php">Goto Site</a></li>