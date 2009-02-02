<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class Finance extends CodonModule
{
	
	public function HTMLHead()
	{
		switch($this->get->page)
		{
			case 'addexpense':
			case 'editexpense':
			case 'viewexpenses':
			
				Template::Set('sidebar', 'sidebar_expenses.tpl');
				
				break;
		}
		
	}
	
	public function Controller()
	{
		
		switch($this->get->page)
		{
			case 'viewcurrent':
			case 'viewreport':
		
				$type = $this->get->type;
							
				/**
				 * Check the first letter in the type
				 * m#### - month
				 * y#### - year
				 * 
				 * No type indicates to view the 'overall'
				 */
				if($type[0] == 'm')
				{
					$type = str_replace('m', '', $type);
					$period = date('F Y', $type);
					
					$data = FinanceData::GetMonthBalanceData($period);
					
					Template::Set('title', 'Balance Sheet for '.$period);
					Template::Set('allfinances', $data);
					
					Template::Show('finance_balancesheet.tpl');
				}
				elseif($type[0] == 'y')
				{
					$type = str_replace('y', '', $type);

					$data = FinanceData::GetYearBalanceData($type);
					
					Template::Set('title', 'Balance Sheet for Year '.date('Y', $type));
					Template::Set('allfinances', $data);
					Template::Set('year', date('Y', $type));
					
					Template::Show('finance_summarysheet.tpl');
				}
				else
				{
					// This should be the last 3 months overview
					
					$data = FinanceData::GetRangeBalanceData('-3 months', 'now');
					
					Template::Set('title', 'Balance Sheet for Last 3 Months');
					Template::Set('allfinances', $data);					
					Template::Show('finance_summarysheet.tpl');
				}			
				
				
				break;
				
			case 'viewexpenses':
			
				if($this->post->action == 'addexpense' || $this->post->action == 'editexpense')
				{
					$this->ProcessExpense();
				}
				
				if($this->get->action == 'deleteexpense')
				{
					FinanceData::RemoveExpense($this->post->id);
				}
			
				Template::Set('allexpenses', FinanceData::GetAllExpenses());
				Template::Show('finance_expenselist.tpl');
			
				break;
				
			case 'addexpense':
				
				Template::Set('title', 'Add Expense');
				Template::Set('action', 'addexpense');
				
				Template::Show('finance_expenseform.tpl');
				
				break;
				
			case 'editexpense':
			
				Template::Set('title', 'Edit Expense');
				Template::Set('action', 'editexpense');
				Template::Set('expense', FinanceData::GetExpenseDetail($this->get->id));
				
				Template::Show('finance_expenseform.tpl');
							
				break;
		}		
	}
	
	public function ProcessExpense()
	{
		
		if($this->post->name == '' || $this->post->cost == '')
		{
			Template::Set('message', 'Name and cost must be entered');
			Template::Show('core_error.tpl');
			return;
		}
		
		if(!is_numeric($this->post->cost))
		{
			Template::Set('message', 'Cost must be a numeric amount, no symbols');
			Template::Show('core_error.tpl');
			return;
		}
		
		if($this->post->action == 'addexpense')
		{
			# Make sure it doesn't exist
			if(FinanceData::GetExpenseByName($this->post->name))
			{
				Template::Set('message', 'Expense already exists!');
				Template::Show('core_error.tpl');
				return;				
			}
			
			$ret = FinanceData::AddExpense($this->post->name, $this->post->cost, $this->post->type);
			Template::Set('message', 'The expense "'.$this->post->name.'" has been added');
		}
		elseif($this->post->action == 'editexpense')
		{
			$ret = FinanceData::EditExpense($this->post->id, $this->post->name, $this->post->cost, $this->post->type);
			Template::Set('message', 'The expense "'.$this->post->name.'" has been edited');
		}
		
		if(!$ret)
		{
			Template::Set('message', 'Error: '.DB::error());
			Template::Show('core_error.tpl');
			
			return;
		}
		
		
		Template::Show('core_success.tpl');
	}	
}