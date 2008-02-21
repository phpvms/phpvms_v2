<?php

/* Static implementation for TemplateSet
*/


class Template
{
	static $tplset;
	
	public function SetTemplatePath($path)
	{
		self::$tplset = new TemplateSet();
		self::$tplset->SetTemplatePath($path);
	}
	
	public function EnableCaching($bool=true)
	{
		self::$tplset->enable_caching = $bool;
	}
	
	public function ClearVars()
	{
		self::$tplset->ClearVars();
	}
	
	public function Set($name, $value)
	{
		return self::$tplset->Set($name, $value);
	}
	
	public function Show($tpl_name)
	{
		return self::$tplset->ShowTemplate($tpl_name);
	}
	
	public function ShowTemplate($tpl_name)
	{
		return self::$tplset->ShowTemplate($tpl_name);
	}
	
	public function GetTemplate($tpl_path, $ret=false)
	{
		return self::$tplset->GetTemplate($tpl_path, $ret);
	}
	
	public function ShowModule($ModuleName, $Method)
	{
		return self::$tplset->ShowModule($ModuleName, $Method);
	}
	
}

?>