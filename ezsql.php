<?php


include './core/codon.config.php';


$_POST= array('name'=>'hey',
	  		  'email'=>'boo',
			  'ignore'=>'NOW()');

$allowed = array('name', 'email');

DB::quick_insert('table', $fields, '', $allowed);
DB::quick_update('table', $fields, "id=1", $allowed);

try
{
	$results = DB::get_results('SELECT * FROM non_existent_table');
} 
catch (ezSQL_Error $e)
{
	echo 'Error occured: ' . $e->error . ', code: ' . $e->errno . '<br />';
	echo 'Last query: '. $e->last_query;
}

DB::$use_exceptions = false;

$results = DB::get_results('SELECT * FROM non_existent_table');

if(!$results)
{
	echo 'Error occured: ' . DB::$error . ', code: ' . DB::$errno . '<br />';
	echo 'Last query: '. DB::$last_query;
}