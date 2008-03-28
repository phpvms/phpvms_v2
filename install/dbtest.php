<?php

include 'bootloader.inc.php';

if(!DB::init($_POST['DBASE_TYPE']))
{
	Template::Set('message', 'There was an error initializing the database');
	Template::Show('error.tpl');
	return false;
}

$ret = DB::connect($_POST['DBASE_USER'], $_POST['DBASE_PASS'], $_POST['DBASE_NAME'], $_POST['DBASE_SERVER']);


if($ret == false)
{
	Template::Set('message', DB::error());
	Template::Show('error.tpl');
	return false;
}

if(!DB::select($_POST['DBASE_NAME']))
{
	Template::Set('message', DB::error());
	Template::Show('error.tpl');
	return false;
}

Template::Set('message', 'Database connection is ok!');
Template::Show('success.tpl');

?>