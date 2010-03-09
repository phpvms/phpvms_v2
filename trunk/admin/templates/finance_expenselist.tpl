<h3>Current Expenses</h3>
<?php
if(!$allexpenses)
{
	echo '<p>No expenses have been added</p>';
	return;
}

$expense_list = Config::Get('EXPENSE_TYPES');
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Name</th>
	<th>Price</th>
	<th>Type</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allexpenses as $expense)
{
?>
<tr>
	<td align="center"><?php echo $expense->name; ?></td>
	<td align="center"><?php 
	
	if($expense->type == 'P' || $expense->type == 'G')
		echo $expense->cost.'%'; 
	else
		echo Config::Get('MONEY_UNIT').$expense->cost; 
	
	?></td>
	<td align="center"><?php echo $expense_list[$expense->type]; ?></td>
	<td align="center" width="1%" nowrap>
		<a id="dialog" class="jqModal" 
			href="<?php echo SITE_URL?>/admin/action.php/finance/editexpense?id=<?php echo $expense->id;?>">
		<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>
		
		<a href="<?php echo SITE_URL?>/admin/action.php/finance/viewexpenses" action="deleteexpense"
			id="<?php echo $expense->id;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a>
	</td>
</tr>
	<?php
}
?>
</tbody>
</table>