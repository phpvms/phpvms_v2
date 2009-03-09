<div id="awardslist">
<h3>Pilot Awards</h3>
<?php
if(!$allawards)
{
	echo 'This pilot has no awards!</div>';
	return;
}


print_r($allawards);
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Award</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php 
	foreach($allawards as $award)
	{
?>
	<tr>
		<td><?php echo $award->name .': '.$award->descrip?></td>
		<td><a href="<?php echo SITE_URL?>/admin/action.php/pilotadmin/pilotawards" action="deleteaward" 
			id="<?php echo $award->id;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a>
		</td>
	</tr>

<?php		
	}
?>
</tbody>
</table>
</div>