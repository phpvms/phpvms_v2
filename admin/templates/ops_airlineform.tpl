<h3><?=$title;?></h3>
<form id="form" action="action.php?admin=airlines" method="post">
<dl>
<dt>Airline Code</dt>
<dd><input name="code" type="text" value="<?=$airline->code; ?>" /></dd>

<dt>Airline Name</dt>
<dd><input name="name" type="text" value="<?=$airline->name; ?>" /></dd>

<dt></dt>
<dd><input type="hidden" name="id" value="<?=$airline->id;?>" />
	<input type="hidden" name="action" value="<?=$action;?>" />
	<input type="submit" name="submit" value="<?=$title;?>" />
</dd>
</dl>
</form>