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
			
				$this->set('sidebar', 'sidebar_expenses.tpl');
				
				break;
		}
		
	}
	
	public function viewcurrent()
	{
		$this->viewreport();
	}
	
	public function viewreport()
	{
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
			
			$this->set('title', 'Balance Sheet for '.$period);
			$this->set('allfinances', $data);
			
			$this->render('finance_balancesheet.tpl');
		}
		elseif($type[0] == 'y')
		{
			$type = str_replace('y', '', $type);

			$data = FinanceData::GetYearBalanceData($type);
			
			$this->set('title', 'Balance Sheet for Year '.date('Y', $type));
			$this->set('allfinances', $data);
			$this->set('year', date('Y', $type));
			
			$this->render('finance_summarysheet.tpl');
		}
		else
		{
			// This should be the last 3 months overview
			
			$data = FinanceData::GetRangeBalanceData('-3 months', 'Today');
			
			$this->set('title', 'Balance Sheet for Last 3 Months');
			$this->set('allfinances', $data);					
			$this->render('finance_summarysheet.tpl');
		}
	}
	
	public function viewexpenses()
	{
		if($this->post->action == 'addexpense' || $this->post->action == 'editexpense')
		{
			$this->processExpense();
		}
		
		if($this->post->action == 'deleteexpense')
		{
			FinanceData::removeExpense($this->post->id);
		}
	
		$this->set('allexpenses', FinanceData::GetAllExpenses());
		$this->render('finance_expenselist.tpl');
	}
	
	public function addexpense()
	{
		$this->set('title', 'Add Expense');
		$this->set('action', 'addexpense');
		
		$this->render('finance_expenseform.tpl');
	}
	
	public function editexpense()
	{
		$this->set('title', 'Edit Expense');
		$this->set('action', 'editexpense');
		$this->set('expense', FinanceData::GetExpenseDetail($this->get->id));
		
		$this->render('finance_expenseform.tpl');	
	}
	
	public function processExpense()
	{
		if($this->post->name == '' || $this->post->cost == '')
		{
			$this->set('message', 'Name and cost must be entered');
			$this->render('core_error.tpl');
			return;
		}
		
		if(!is_numeric($this->post->cost))
		{
			$this->set('message', 'Cost must be a numeric amount, no symbols');
			$this->render('core_error.tpl');
			return;
		}
		
		if($this->post->action == 'addexpense')
		{
			# Make sure it doesn't exist
			if(FinanceData::GetExpenseByName($this->post->name))
			{
				$this->set('message', 'Expense already exists!');
				$this->render('core_error.tpl');
				return;				
			}
			
			$ret = FinanceData::AddExpense($this->post->name, $this->post->cost, $this->post->type);
			$this->set('message', 'The expense "'.$this->post->name.'" has been added');
			
			LogData::addLog(Auth::$userinfo->pilotid, 'Added expense "'.$this->post->name.'"');
		}
		elseif($this->post->action == 'editexpense')
		{
			$ret = FinanceData::EditExpense($this->post->id, $this->post->name, $this->post->cost, $this->post->type);
			$this->set('message', 'The expense "'.$this->post->name.'" has been edited');
			LogData::addLog(Auth::$userinfo->pilotid, 'Edited expense "'.$this->post->name.'"');
		}
		
		if(!$ret)
		{
			$this->set('message', 'Error: '.DB::error());
			$this->render('core_error.tpl');
			
			return;
		}
		
		$this->render('core_success.tpl');
	}	
}