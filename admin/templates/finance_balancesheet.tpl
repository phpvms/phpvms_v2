<?php
/*
 * DO NOT EDIT THIS TEMPLATE UNLESS:
 *   1. YOU HAVE ALOT OF TIME
 *   2. YOU DON'T MIND LOSING SOME HAIR
 *   3. YOU HAVE BIG BALLS MADE OF STEEL
 *
 *	It can cause incontinence
 *
 *	YOU HAVE BEEN WARNED!!!
 */
?><?php Template::Show('finance_header.tpl'); ?>
<h3><?php echo $title?></h3>
<?php

	//$total = $pirepfinance->Revenue - $pirepfinance->TotalPay;
		
	# This holds all of our values for the graph
	#	And some default values
	# DO NOT EDIT THESE!!!!!!!!!!!!!!!!!!!!!!!!!!!1111111111111111
	
	$pilotpay_total = $allfinances['pirepfinance']->TotalPay;
	$expense_total = 0;
	$g_expenses_values=array();
	$g_expenses_labels=array();
	
	$allfinances['pirepfinance']->TotalPay  = $allfinances['pirepfinance']->TotalPay * -1;
	$allfinances['pirepfinance']->FlightExpenses  = $allfinances['pirepfinance']->FlightExpenses * -1;
	$allfinances['pirepfinance']->FuelCost  = $allfinances['pirepfinance']->FuelCost * -1;
	
	$running_total = $allfinances['pirepfinance']->Revenue + $allfinances['pirepfinance']->TotalPay + $allfinances['pirepfinance']->FlightExpenses + $allfinances['pirepfinance']->FuelCost;
?>

<table width="550px" class="balancesheet" cellpadding="0" cellspacing="0">

	<tr class="balancesheet_header">
		<td align="" colspan="2">Cash and Sales</td>
	</tr>
	<?php
	/*
	<tr>
		<td align="right">Cash Reserves: </td>
		<td align="right"><?php echo FinanceData::FormatMoney($allfinances['cashreserve']);?></td>
	</tr>
	*/
	?>
	<tr>
		<td align="right">Gross Revenue Flights: <br />
			Total number of flights: <?php echo $allfinances['pirepfinance']->TotalFlights; ?>
		</td>
		<td align="right" valign="top"><?php echo FinanceData::FormatMoney($allfinances['pirepfinance']->Revenue);?></td>
	</tr>
	
	<tr>
		<td align="right">Pilot Payments: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($allfinances['pirepfinance']->TotalPay));?></td>
	</tr>
	<tr>
		<td align="right">Fuel Costs: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($allfinances['pirepfinance']->FuelCost));?></td>
	</tr>
	<tr>
		<td align="right">Flight Expenses: </td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($allfinances['pirepfinance']->FlightExpenses));?></td>
	</tr>
	
	<tr class="balancesheet_header" style="border-bottom: 1px dotted">
		<td align="" colspan="2" style="padding: 1px;"></td>
	</tr>
	
	<tr>
		<td align="right"><strong>Total:</strong></td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($running_total));?></td>
	</tr>
	
	<tr class="balancesheet_header">
		<td align="" colspan="2">Expenses (Monthly)</td>
	</tr>

<?php
	/* COUNT EXPENSES */
	if(!$allexpenses['allexpenses'])
	{
		$allexpenses['allexpenses'] = array();
		?>
		<tr>
		<td align="right">None</td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney(0));?></td>
	</tr>
	<?php
	}
	
	if(!$allfinances['allexpenses']) $allfinances['allexpenses'] = array();
	
	foreach($allfinances['allexpenses'] as $expense)
	{
		# Add the value to the graph
		
		$g_expenses_values[] =  $expense->cost/100;
		$g_expenses_labels[] = $expense->name;
	
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
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($allfinances['total'])); ?></td>
	</tr>
	<tr class="balancesheet_header" style="border-bottom: 1px dotted">
		<td align="" colspan="2" style="padding: 1px;"></td>
	</tr>
	<tr>
		<td align="right"><strong>Net Worth:</strong></td>
		<td align="right"> <?php echo str_replace('$', Config::Get('MONEY_UNIT'), FinanceData::FormatMoney($allfinances['total'])); ?></td>
	</tr>
</table>

<h3>Breakdown</h3>
	<div>
	<strong>Expenses: </strong><br />
	<?php
	/*
		Show the expenses details graphs
		
		IF YOU DO NOT WANT THE GRAPH TO SHOW
		COMMENT OUT THE ECHO BELOW BY ADDING TWO
		// IN FRONT OF IT
		
	*/

	error_reporting(0);
	$graph = new ChartGraph('pchart', 'pie', 600, 400);
	$graph->setTitles('Expenses');
	$graph->AddData($g_expenses_values, $g_expenses_labels);
	echo '<img src="'.$graph->GenerateGraph().'" />'; 
		
	?>
	<br /><br />
	</div>
<div>
<strong>Overall Costs</strong><br />
<?php
/*
	Show the total expenditures graph
	
	IF YOU DO NOT WANT THE GRAPH TO SHOW
	COMMENT OUT THE ECHO BELOW BY ADDING TWO
	// IN FRONT OF IT
	
*/
//$graph = Graphing::GenerateGraph($g_expenses_values, $g_expenses_labels);

$g_expenses_values = array($pilotpay_total, $expense_total);
$g_expenses_labels = array('Pilot Salary','Expenses');

$expense_graph = new ChartGraph('pchart', 'pie3d', 600, 400);
$expense_graph->setTitles('Pilot Salary vs Expenses');
$expense_graph->AddData($g_expenses_values, $g_expenses_labels);
echo '<img src="'.$expense_graph->GenerateGraph().'" />'; 

?>
</div>