<?php

if(!$userinfo)
{
	echo '<h3>This pilot does not exist!</h3>';
	return;
}
?>
<h3>Profile For <?php echo $userinfo->firstname . ' ' . $userinfo->lastname?></h3>
<table>
	<tr>
		<td align="center" valign="top">
			<img src="<?php echo SITE_URL.AVATAR_PATH.'/'.$pilotcode.'.png';?>" alt="No Avatar" /> 
			<br /><br />
			<img src="<?php echo $userinfo->rankimage?>"  alt="" />
		</td>
		<td valign="top">
			<ul>
				<li><strong>Pilot ID: </strong><?php echo $pilotcode ?></li>
				<li><strong>Rank: </strong><?php echo $userinfo->rank;?></li>
				<li><strong>Total Flights: </strong><?php echo $userinfo->totalflights?></li>
				<li><strong>Total Hours: </strong><?php echo Util::AddTime($userinfo->totalhours, $userinfo->transferhours); ?></li>
				<li><strong>Location: </strong>
					<img src="<?php echo Countries::getCountryImage($userinfo->location);?>" 
								alt="<?php echo Countries::getCountryName($userinfo->location);?>" />
					<?php echo Countries::getCountryName($userinfo->location);?> 
				</li>
				<?php
				// Show the public fields
				if($allfields)
				{
					foreach($allfields as $field)
					{
						echo "<li><strong>$field->title: </strong>$field->value</li>";
					}
				}
				?>
			</ul>
			
			<p>
			<strong>Awards</strong>
			<?php
			if(is_array($allawards))
			{			
			?>
			<ul>
				<?php foreach($allawards as $award)
				{ 
					/* To show the image:
					
						<img src="<?php echo $award->image?>" alt="<?php echo $award->descrip?>" />
					*/
				
				?>
					<li><?php echo $award->name ?></li>
				<?php } ?>
			</ul>	
			<?php
			}
			?>
		</p>
		</td>
	
	</tr>
</table>