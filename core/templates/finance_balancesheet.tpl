<?php Template::Show('finance_header.tpl'); ?>
<h3><?php echo $title?></h3>
<?php

	$total = $pirepfinance->Revenue - $pirepfinance->TotalPay;
	
	# Temporary
	$cashreserves = 0;
	
	# This holds all of our values for the graph
	#	And some default values
	$pilotpay_total = $pirepfinance->TotalPay;
	$expense_total = 0;
	$g_expenses_values='';
	$g_expenses_labels='';
	
	# Correct the sign since we're subtracting
	$pirepfinance->TotalPay  = $pirepfinance->TotalPay * -1;
?>

<table width="550px" class="balancesheet" cellpadding="0" cellspacing="0">

	<tr class="balancesheet_header">
		<td align="" colspan="2">Cash and Sales</td>
	</tr>
	
	<tr>
		<td align="right">Cash Reserves: </td>
		<td align="right"><?php echo FinanceData::FormatMoney($cashreserves);?></td>
	</tr>

	<tr>
		<td align="right">Gross Revenue Flights: <br />
			Total number of flights: <?php echo $pirepfinance->TotalFlights; ?>
		</td>
		<td align="right" valign="top"><?php echo FinanceData::FormatMoney($pirepfinance->Revenue);?></td>
	</tr>
	
	<tr>
		<td align="right">Pilot Payments: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($pirepfinance->TotalPay));?></td>
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
			<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney(0));?></td>
		</tr>
	<?php
	}
	
	foreach($allexpenses as $expense)
	{
		# Add the value to the graph
		
		$g_expenses_values .= $expense->cost/100 .',';
		$g_expenses_labels .= $expense->name .'|';
	
		$expense_total += $expense->cost;
		
		$expense->cost  = $expense->cost * -1;
		$total = $total + $expense->cost;
?>		
	<tr>
		<td align="right"><?php echo $expense->name?>: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($expense->cost));?></td>
	</tr>
<?php		
	}
?>
	<tr class="balancesheet_header" style="border-bottom: 1px dotted">
		<td align="" colspan="2" style="padding: 1px;"></td>
	</tr>
	<tr>
		<td align="right"><strong>Expenses Total:</strong></td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($expense_total));?></td>
	</tr>
	
	<tr class="balancesheet_header">
		<td align="" colspan="2">Totals</td>
	</tr>
	
	<tr style="border: 0px">
		<td align="right">Total Revenue: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($total)); ?></td>
	</tr>
	<tr class="balancesheet_header" style="border-bottom: 1px dotted">
		<td align="" colspan="2" style="padding: 1px;"></td>
	</tr>
	<tr>
		<td align="right"><strong>Net Worth:</strong></td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($total+$cashreserves)); ?></td>
	</tr>
</table>

<h3>Breakdown</h3>
<div>
<strong>Expenses: </strong>
<?php
/*
	Show the expenses details graph
	
*/
$g_expenses_values = substr($g_expenses_values, 0, strlen($g_expenses_values)-1);
$g_expenses_labels = substr($g_expenses_labels, 0, strlen($g_expenses_labels)-1);
// GRAPH

$chart = new googleChart($g_expenses_values, 'p3');
$chart->dimensions = '500x180';
$chart->setLabels($g_expenses_labels);

echo '<img src="'.$chart->draw(false).'" />';
	
?>
<br /><br />
</div>
<div>
<strong>Overall Costs</strong>
<?php
/*
	Show the total expenditures graph
	
*/
$g_expenses_values = "$pilotpay_total, $expense_total";
$g_expenses_labels = "Pilot Salary|Expenses";

// GRAPH
$chart = new googleChart($g_expenses_values, 'p3');
$chart->dimensions = '500x180';
$chart->setLabels($g_expenses_labels);

echo '<img src="'.$chart->draw(false).'" />';

?>
</div>