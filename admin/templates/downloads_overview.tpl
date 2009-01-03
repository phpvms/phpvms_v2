<h3>Downloads</h3>
<?php
if(!$allcategories)
{
	echo 'No categories or downloads have been added!';
	$allcategories = array();
}

foreach($allcategories as $category)
{
?>
	<h3><?php echo $category->name?> 
		<span style="font-size: 8pt">[<a id="dialog" class="jqModal" href="<?php echo SITE_URL?>/admin/action.php/downloads/editcategory?id=<?php echo $category->id?>">Edit</a>] | [<a class="ajaxcall" action="deletecategory" id="<?php echo $category->id?>" href="<?php echo SITE_URL?>/admin/action.php/downloads">Delete</a>] | [<a id="dialog" class="jqModal" 
				href="<?php echo SITE_URL?>/admin/action.php/downloads/adddownload?cat=<?php echo $category->id?>">Add Download</a>]</span>
	</h3>
<?php
	$alldownloads = DownloadData::GetDownloads($category->id);
	
	if(!$alldownloads)
	{
		echo 'There are no downloads under this category.';
	}
	else
	{
?>
	<table id="tabledlist" class="tablesorter">
		<thead>
		<tr>
			<th>Download Name</th>
			<th>Download Count</th>
			<th>Options</th>
		</tr>
		</thead>
		<tbody>
<?php	foreach($alldownloads as $download) { 
?>
		<tr>
			<td><?php echo '<a href="'.$download->link.'">'.$download->name.'</a>' ?></td>
			<td><?php echo ($download->hits=='')? '0' : $download->hits?></td>
			<td width="1%" nowrap>
				<a id="dialog" class="jqModal" 
					href="<?php echo SITE_URL?>/admin/action.php/downloads/editdownload?id=<?php echo $download->id?>">
					<img src="<?echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>					
				<a class="ajaxcall" action="deletedownload" id="<?php echo $download->id?>"
					href="<?php echo SITE_URL?>/admin/action.php/downloads">
					<img src="<?echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a>	
					
			</td>
		</tr>
<?php	} 

?>
		</tbody>
		</table>
<?php
	 }
}
?>