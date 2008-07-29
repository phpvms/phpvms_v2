<h3><?=$title?></h3>
<form id="form" action="action.php?admin=airports" method="post">
<dl>
<dt>Airport ICAO Code</dt>
<dd><input id="airporticao" name="icao" type="text" value="<?=$airport->icao?>" /> <button id="lookupicao">Look Up</button></dd>

<dt></dt>
<dd><div id="statusbox"></div></dd>
<dt>Airport Name</dt>
<dd><input id="airportname" name="name" type="text" value="<?=$airport->name?>" /></dd>

<dt>Country Name</dt>
<dd><input id="airportcountry" name="country" type="text" value="<?=$airport->country?>"  /></dd>

<dt>Latitude</dt>
<dd><input id="airportlat" name="lat" type="text" value="<?=$airport->lat?>" /></dd>

<dt>Longitude</dt>
<dd><input id="airportlong" name="long" type="text" value="<?=$airport->lng?>" /></dd>

<dt>Hub</dt>
<?php
	if($airport->hub == '1')
		$checked = 'checked ';
	else
		$checked = '';
?>
<dd><input name="hub" type="checkbox" value="true" <?=$checked?>/></dd>

<dt></dt>
<dd><input type="hidden" name="action" value="<?=$action?>" />
	<input type="submit" name="submit" value="<?=$title?>" />
</dd>
</dl>
</form>