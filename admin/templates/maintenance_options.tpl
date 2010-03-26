<h3>Maintenance Options</h3>

<dl>
	<dt><strong>
		<a href="<?php echo SITE_URL?>/admin/index.php/maintenance/resethours">Reset Hours</a>:
		</strong>
	</dt>
	<dd>This will reset your VA's total hours count<br /></dd>
	
	<dt><strong>
		<a href="<?php echo SITE_URL?>/admin/index.php/maintenance/resetacars">Reset ACARS</a>:
		</strong>
	</dt>
	<dd>Empties the ACARS table, if you're having problems with ACARS updates<br /></dd>
	
	<dt><strong>
		<a href="<?php echo SITE_URL?>/admin/index.php/maintenance/resetsignatures">Reset Signatures</a>: 
		</strong>
	</dt>
	<dd>Select this option to reset your member's signatures. If you change the background, regenerate them.
	<br />
	</dd>
	
	<dt><strong>
		<a href="<?php echo SITE_URL?>/admin/index.php/maintenance/resetdistances">Recalculate Distances</a>: 
		</strong>
	</dt>
	<dd>Select this to re-calcuate all the distances in your schedules and PIREPS. Useful for a import. Accurate 
	distances are required for some reports.<br /></dd>
	
	<dt><strong>
		<a href="<?php echo SITE_URL?>/admin/index.php/maintenance/resetpirepcount">Reset Pilot PIREP Count</a>:
		</strong>
	</dt>
	<dd>Reset flight count totals for pilots. Use if the PIREP counts are off.<br /></dd>
	
	<dt><strong>
		<a href="<?php echo SITE_URL?>/admin/index.php/maintenance/resetpilotpay">Reset Pilot Payments</a>:
		</strong>
	</dt>
	<dd>Scans the PIREPs and flighttimes, adjust pilot pay to the rate indicated for that pilot in that PIREP.<br /></dd>
	
	<dt><strong>
		<a href="<?php echo SITE_URL?>/admin/index.php/maintenance/resetpirepfinance">Reset PIREP Finances</a>:
		</strong>
	</dt>
	<dd>This adds financial data to existing PIREPS which do not have any. NOTICE! This will reset <strong>all</strong> of your PIREPS to the current finances (expenses, fuel prices, etc)<br /></dd>
	
	<dt><strong>
		<a href="<?php echo SITE_URL?>/admin/index.php/maintenance/resetscheduleroute">Reset cached Schedule routes</a>:
		</strong>
	</dt>
	<dd>The details of a route are cached, this resets the cache (doesn't affect the entered route), for schedules.<br /></dd>
	
	<dt><strong>
		<a href="<?php echo SITE_URL?>/admin/index.php/maintenance/resetpireproute">Reset cached PIREP routes</a>:
		</strong>
	</dt>
	<dd>The details of a route are cached, this resets the cache (doesn't affect the entered route), for PIREPs. <br /></dd>
</dl>