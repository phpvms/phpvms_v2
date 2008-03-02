<div id="tabcontainer">
	<ul>
		<li><a href="#pilotdetails"><span>Pilot Details</span></a></li>
		<li><a href="#customfields"><span>Custom Fields</span></a></li>
		<li><a href="#pireps"><span>View PIREPs</span></a></li>
		<li><a href="#resetpass"><span>Password Reset</span></a></li>
	</ul>
	<div id="pilotdetails">
		<?php Template::Show('pilots_details.tpl'); ?>
	</div>
	<div id="customfields">
		<?php Template::Show('pilots_customfields.tpl'); ?>
	</div>
	<div id="pireps">
		<?php Template::Show('pilots_pireps.tpl'); ?>
	</div>
	<div id="resetpass">
		<?php Template::Show('pilots_resetpass.tpl'); ?>
	</div>
</div>