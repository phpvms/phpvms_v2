<?php Template::Show('finance_header.tpl'); ?>
<h3><?php echo $title?></h3>
<?php

	$total = 0;
	
?>

<table width="550px" class="balancesheet" cellpadding="0" cellspacing="0">

	<tr class="balancesheet_header" style="text-align: center">
		<td align="left">Month</td>
		<td align="center">Flights</td>
		<td align="left">Revenue</td>
		<td align="center">Pilot Pay</td>
		<td align="center">Expenses</td>
		<td align="center">Total</td>
	</tr>
	
<?php 
foreach ($allfinances as $month)
{
?>
	<tr>
		<td align="right">
			<?php 
			$months .= date('M', $month['timestamp']).'|';
			echo date('F', $month['timestamp']);
			?>
		</td>
		<td align="center">
		<?php 
			$flights .= $month['pirepfinance']->TotalFlights.',';
			echo ($month['pirepfinance']->TotalFlights=='') ? 0 : $month['pirepfinance']->TotalFlights;
		?>
		</td>
		<td align="right">
			<?php 
			$revenue .= $month['pirepfinance']->Revenue.',';
			echo FinanceData::FormatMoney($month['pirepfinance']->Revenue);
			?>
		</td>
		<td align="right">
			<?php 
			$pilot_pay.= $month['pirepfinance']->TotalPay.',';
			echo FinanceData::FormatMoney($month['pirepfinance']->TotalPay);
			?>
		</td>
		<td align="right">
			<?php 
			$expenses.=$month['totalexpenses'].',';
			echo FinanceData::FormatMoney($month['totalexpenses']);
			?>
		</td>
		<td align="right">
			<?php 
			$profit .= $month['total'].',';
			$total+=$month['total'];
			echo FinanceData::FormatMoney($month['total']);
			?>
		</td>
	</tr>
<?php
}
?>
<tr class="balancesheet_header" style="border-bottom: 1px dotted">
	<td align="" colspan="6" style="padding: 1px;"></td>
</tr>
	
<tr>
	<td align="right" colspan="5"><strong>Total:</strong></td>
	<td align="right"><strong><?php echo FinanceData::FormatMoney($total);?></strong></td>
</tr>
	
</table>

<h3>Breakdown</h3>

<?php
$profit = substr($profit, 0, strlen($profit)-1);
$revenue = substr($revenue, 0, strlen($revenue)-1);
$expenses = substr($expenses, 0, strlen($revenue)-1);

# Months labels
$months = substr($months, 0, strlen($months)-1);

/*
	Show the revenue details graph
*/
$chart = new googleChart('', 'line', 'Revenue for '.$year, '500x200');
$chart->negativeMode = true;
$chart->loadData($profit);
$chart->setLabels($months, 'bottom');

echo '<img src="'.$chart->draw(false).'" />';
	
?>
<br /><br />

<?php
/*
	Show the expenses details graph
*/
$chart = new googleChart($expenses, 'line', 'Expenses for '.$year, '500x200');
$chart->setLabels($months, 'bottom');

echo '<img src="'.$chart->draw(false).'" />';

?>