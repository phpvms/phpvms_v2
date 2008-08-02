<li><a href="?admin=">Dashboard</a></li>
<li><a href="#">Site</a>
	<ul>
		<li><a href="<?SITE_URL ?>/admin/index.php/sitecms/viewnews">Site News</a></li>
		<li><a href="<?SITE_URL ?>/admin/index.php/sitecms/viewpages">Site Pages</a></li>
	</ul>
</li>
<li><a href="?admin=airlines">Operations</a>
	 <ul>
		<li><a href="<?SITE_URL ?>/admin/index.php/operationsadmin/airlines">1. Airlines</a></li>
		<li><a href="?admin=aircraft">2. Aircraft</a></li>
		<li><a href="?admin=airports">3. Airports</a></li>
		<li><a href="?admin=schedules">4. Flight Schedules</a></li>
	</ul>
</li>
<li><a href="#">PIREPs</a>
	<ul>
		<li><a href="?admin=viewpending">View Pending</a></li>
		<li><a href="?admin=viewrecent">View Recent Reports</a></li>
		<li><a href="?admin=viewall">View All Reports</a></li>
	</ul>
</li>
<li><a href="#">Pilots</a>
	<ul>
		<li><a href="?admin=pendingpilots">Pending Registrations</a></li>
		<li><a href="?admin=viewpilots">View Registered Pilots</a></li>
		<li><a href="?admin=pilotgroups">Pilot Groups</a></li>
	</ul>
</li>
<li><a href="#">Settings</a>
	<ul>
		<li><a href="?admin=settings">General Settings</a></li>
		<li><a href="?admin=customfields">Profile Fields</a></li>
		<li><a href="?admin=pirepfields">PIREP Fields</a></li>
		<li><a href="?admin=pilotranks">Pilot Ranks</a></li>
		<li><a href="?admin=about">About phpVMS</a></li>
	</ul>
</li>
<?=$MODULE_NAV_INC;?>
<li><a href="<?=SITE_URL?>/Login/logout">Log Out</a></li>
<li><a href="<?=SITE_URL?>/index.php">Goto Site</a></li>