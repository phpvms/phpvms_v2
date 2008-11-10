<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>phpVMS Installer</title>

<script type="text/javascript" src="../lib/js/jquery.min.js"></script>

<style>
body
{
	font-family: Verdana;
	font-size: 12px;
}

#error
{
	border: 1px solid #FF0000;
	background: #FFCCDA;
	padding: 7px;
	text-align: center;
}

#success
{
	border: 1px solid #008020;
	background: #D8FFCC;
	padding: 7px;
	text-align: center;
}

#copyright
{
	font-size: 10px;
}

td input
{
	width: 385px;
	padding: 5px;
	border: 1px solid #CCCCCC;
}
</style>

<script type="text/javascript">
$(document).ready(function() {
$("#dbcheck").bind('click', function()
{
	$("#dbtest").load("dbtest.php", {DBASE_USER: $("#DBASE_USER").val(), DBASE_PASS: $("#DBASE_PASS").val(), DBASE_NAME: $("#DBASE_NAME").val(), DBASE_SERVER: $("#DBASE_SERVER").val(), DBASE_TYPE: $("#DBASE_TYPE").val()});
});
});

</script>

</head>
<body>

<table width="770px" align="center" style="border: 1px solid #999999; padding-bottom: 10px;">
<tr>
	<td align="center">
		<img src="lib/logo.jpg" />
	</td>
</tr>
<tr>
	<td>