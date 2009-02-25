<li><a href="<?php echo SITE_URL?>/admin/index.php/dashboard">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/dashboard_icon.png" />Dashboard
	</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/dashboard">Site Overview</a></li>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/site_icon.png" />News & Content
	</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/sitecms/viewnews">News</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/sitecms/viewpages">Pages</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/downloads/overview">Downloads</a></li>
	</ul>
</li>
<li><a class="menu" href="?admin=airlines">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/operations_icon.png" />Airline Operations
	</a>
	 <ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/airlines">Add & Edit Airlines</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/aircraft">Add & Edit Fleet</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/airports">Add & Edit Airports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/schedules">Flight Schedules & Routes</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/import">Import Schedules</a></li>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/pilots_icon.png" />Pilots & Groups</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/pendingpilots">Pending Registrations</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots">View All Pilots</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/pilotgroups">Pilot Groups</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotranking/pilotranks">Pilot Ranks</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotranking/awards">Awards</a></li>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/pireps_icon.png" />Pilot Reports (PIREPS)</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewpending">View Pending</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewrecent">View Recent Reports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewall">View All Reports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings/pirepfields">PIREP Fields</a></li>
	</ul>
</li>
<li><a class="menu" href="<?php echo SITE_URL?>/admin/index.php/reports">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/reports_icon.png" />Reports & Expenses</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/reports">Overview</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/finance/viewcurrent">Financial Reports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/reports/aircraft">Aircraft Reports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/finance/viewexpenses">Expenses</a></li>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/settings_icon.gif" />Site & Settings</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings">General Settings</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/maintenance/options">Maintenance Options</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings/customfields">Profile Fields</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/dashboard/about">About phpVMS</a></li>
	</ul>
</li>
<?php echo $MODULE_NAV_INC;?>