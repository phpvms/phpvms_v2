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


<div id="tabcontainer" class="flora">
          <ul>

                <li><a href="#fragment-1"><span>One</span></a></li>
                <li><a href="#fragment-2"><span>Two</span></a></li>
                <li><a href="#fragment-3"><span>Three</span></a></li>
            </ul>
            <div id="fragment-1">
                <p>First tab is active by default:</p>
                <pre><code>$('#example > ul').tabs();</code></pre>

            </div>
            <div id="fragment-2">
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
            </div>
            <div id="fragment-3">
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
            </div>
        </div>