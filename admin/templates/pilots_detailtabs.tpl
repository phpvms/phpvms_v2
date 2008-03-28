<div id="dialogresult"></div>
<div id="tabcontainer">
	<ul>
		<li><a href="#pilotdetails"><span>Pilot Details</span></a></li>
		<li><a href="#pilotgroups"><span>Pilot Groups</span></a></li>
		<li><a href="#pireps"><span>View PIREPs</span></a></li>
		<li><a href="#resetpass"><span>Pilot Options</span></a></li>
	</ul>
	<div id="pilotdetails">
		<?php Template::Show('pilots_details.tpl'); ?>
	</div>
	<div id="pilotgroups">
		<?php Template::Show('pilots_groups.tpl'); 
			  Template::Show('pilots_addtogroup.tpl');
		?>
	</div>
	<div id="pireps">
		<?php Template::Show('pilots_pireps.tpl'); ?>
	</div>
	<div id="resetpass">
		<?php Template::Show('pilots_options.tpl'); ?>
	</div>
</div>
<div align="right" style="clear:both;"><input type="button" class="jqmClose" name="jqmClose" value="Close" /></div>