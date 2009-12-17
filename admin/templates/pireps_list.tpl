<div id="pireplist">
<?php
if($title!='')
	echo "<h3>$title</h3>";
?>
<p><?php if(isset($descrip)) { echo $descrip; }?></p>
<?php
if(!$pireps)
{
	echo '<p>No reports have been found</p>';
	return;
}
?>
<p>There are a total of <?php echo count($pireps);?> flight reports in this category. <a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/approveall">Click to approve all</a></p>
<?php
if(isset($paginate))
{
?>
<div style="float: right;">
	<a href="?admin=<?php echo $admin?>&start=<?php echo $start?>">Next Page</a>
	<br />
</div>
	<?php
}
?>
<table id="tabledlist" class="tablesorter" style="height: 100%">
<thead>
<tr>
	<th colspan="4">Details</th>
</tr>
</thead>
<tbody>
<?php
foreach($pireps as $pirep)
{	
	/* Two different templates, since we want to show the pending pireps
		differently than the ones which are processed. Two separate templates
		are much easier than one littered with if/else's all over the place
	 */
	 
	//Template::Set('pirep', $pirep);
	if($pirep->accepted == PIREP_PENDING)
	{
		include dirname(__FILE__).'/pirep_pending.tpl';
	}
	else
	{
		include dirname(__FILE__).'/pirep_processed.tpl';
	}
	
	
} /* Close the PIREPs loop */
?>
</tbody>
</table>
<span style="float: right">* - double click to select</span><br />

<?php
if(isset($paginate))
{
?>
<div style="float: right;">
	<a href="?admin=<?php echo $admin?>&start=<?php echo $start?>">Next Page</a>
	<br />
</div>
<?php
} /* Close the paginate loop */
?>
</div>