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
			case 'viewexpenses':
			
				if($this->post->action == 'addexpense')
				{
					$this->AddExpense();
				}
				elseif($this->post->action == 'editexpense')
				{
					$this->EditExpense();
				}
			
				Template::Set('allexpenses', FinanceData::GetAllExpenses());
			
				break;
				
			case 'addexpense':
				
				Template::Set('title', 'Add Expense');
				Template::Set('action', 'addexpense');
				
				Template::Show('finance_expenseform.tpl');
				
				break;
				
			case 'editexpense':
			
				Template::Set('title', 'Edit Expense');
				Template::Set('action', 'editexpense');
				
				Template::Show('finance_expenseform.tpl');
							
				break;
		}		
	}
	
	public function AddExpense()
	{
		
		
	}
	
	public function EditExpense()
	{
		
		
	}
}