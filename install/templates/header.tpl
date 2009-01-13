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
	background: #666666;
	color: #000000;
	margin: 0;
	padding: 0;
}

a {
	color: #333;
	text-decoration: none;
}

a:hover {
	color: #000;
}

.header { 
	padding: 0px;
	background: url(../admin/lib/layout/images/background.png) repeat-x;
	border-bottom: 2px solid #000;
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

table {
	padding: 0px;
	background: #FFF;
}

td {
	padding: none;
}

td input
{
	width: 385px;
	padding: 5px;
	border: 1px solid #CCCCCC;
}

.footer { 
	padding: 0 10px;
	background:#DDDDDD;
	border-top: 2px solid #000;
} 

.footer td {
	padding: 10px;
}

.footer p {
	margin: 0; 
	padding: 10px 0;
	text-align: right;
	font-size: 7pt;
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

<table width="770px" style="padding: 0px" align="center" cellpadding="0" cellspacing="0">
<tr style="padding: 0px; margin: 0px;">
	<td class="header" style="padding: 0px; margin: 0px;">
		<img src="../admin/lib/layout/images/admin_logo.png" />
	</td>
</tr>
</table>

<table width="770px" align="center" style="padding-bottom: 10px;">
<tr>
	<td align="center">