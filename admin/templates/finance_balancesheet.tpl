<h3>Balance Sheet</h3>
<?php

	$total = $pirepfinance->Revenue - $pirepfinance->TotalPay;
	
	# Temporary
	$cashreserves = 0;
?>

<p>Total number of flights: <?php echo $pirepfinance->TotalFlights; ?></p>
<table width="500px" class="balancesheet">

	<tr class="balancesheet_header">
		<td align="" colspan="2">Cash and Sales</td>
	</tr>
	
	<tr>
		<td align="right">Cash Reserves: </td>
		<td align="right"><?php echo str_replace('$', Config::Get('MONEY_UNIT'), money_format(Config::Get('MONEY_FORMAT'), $cashreserves));?></td>
	</tr>

	<tr>
		<td align="right">Gross Revenue Flights: </td>
		<td align="right"><?php echo str_replace('$', Config::Get('MONEY_UNIT'), money_format(Config::Get('MONEY_FORMAT'), $pirepfinance->Revenue));?></td>
	</tr>
	
	<tr>
		<td align="right">Pilot Payments: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), money_format(Config::Get('MONEY_FORMAT'), -1*$pirepfinance->TotalPay));?></td>
	</tr>
	
	<tr class="balancesheet_header">
		<td align="" colspan="2">Expenses (Monthly)</td>
	</tr>


<?php
	/* COUNT EXPENSES */
	if(!$allexpenses)
	{
		$allexpenses = array();
		?>
		<tr>
		<td align="right">None</td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), money_format(Config::Get('MONEY_FORMAT'), 0));?></td>
	</tr>
	<?php
	}
	
	foreach($allexpenses as $expense)
	{
		$expense->cost  = $expense->cost * -1;
		$total = $total + $expense->cost;
?>		
	<tr>
		<td align="right"><?php echo $expense->name?>: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), money_format(Config::Get('MONEY_FORMAT'), $expense->cost));?></td>
	</tr>
<?php		
	}
?>
	<tr class="balancesheet_header">
		<td align="" colspan="2">Totals</td>
	</tr>
	
	<tr>
		<td align="right">Total Revenue: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), money_format(Config::Get('MONEY_FORMAT'), $total)); ?></td>
	</tr>
	<tr class="balancesheet_header">
		<td align="" colspan="2" style="padding: 1px;"></td>
	</tr>
	<tr>
		<td align="right">Net Worth: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), money_format(Config::Get('MONEY_FORMAT'), $total+$cashreserves)); ?></td>
	</tr>
</table>