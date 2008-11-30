<h3>Admin Panel</h3>
<p>Welcome back to the admin panel, <?php echo Auth::$userinfo->firstname?>!</p>

<h3>Latest Stats</h3>
<p>
<strong><?php echo  count(PIREPData::GetAllReportsByAccept(PIREP_PENDING))?></strong> PIREPs pending<br />
<strong><?php echo  count(PilotData::GetPendingPilots())?></strong> Pilot registrations pending
</p>