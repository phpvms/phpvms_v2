<h3><?=$title;?></h3>
<form id="form" action="action.php?admin=aircraft" method="post">
<dl>
<dt>Aircraft ICAO Code</dt>
<dd><input name="icao" type="text" value="<?=$aircraft->icao; ?>" /></dd>

<dt>Aircraft Name (i.e B747-400)</dt>
<dd><input name="name" type="text" value="<?=$aircraft->name; ?>" /></dd>

<dt>Full Name (Boeing 747-400 Combi)</dt>
<dd><input name="fullname" type="text"  value="<?=$aircraft->fullname; ?>" /></dd>

<dt>Range</dt>
<dd><input name="range" type="text"   value="<?=$aircraft->range; ?>" /></dd>

<dt>Weight</dt>
<dd><input name="weight" type="text" value="<?=$aircraft->weight; ?>" /></dd>

<dt>Cruise</dt>
<dd><input name="cruise" type="text"  value="<?=$aircraft->cruise; ?>" /></dd>

<dt></dt>
<dd><input type="hidden" name="id" value="<?=$aircraft->id;?>" />
	<input type="hidden" name="action" value="<?=$action;?>" />
	<input type="submit" name="submit" value="<?=$title;?>" />
</dd>
</dl>
</form>