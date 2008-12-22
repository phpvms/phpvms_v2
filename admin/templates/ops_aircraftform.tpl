<h3><?php echo $title;?></h3>
<form id="form" action="<?php echo SITE_URL?>/admin/action.php/operations/aircraft" method="post">
<dl>
<dt>Aircraft ICAO Code</dt>
<dd><input name="icao" type="text" value="<?php echo $aircraft->icao; ?>" /></dd>

<dt>Aircraft Name/Type (i.e B747-400)</dt>
<dd><input name="name" type="text" value="<?php echo $aircraft->name; ?>" /></dd>

<dt>Full Name (Boeing 747-400 Combi)</dt>
<dd><input name="fullname" type="text"  value="<?php echo $aircraft->fullname; ?>" /></dd>

<dt>Aircraft Registration</dt>
<dd><input name="registration" type="text"  value="<?php echo $aircraft->registration; ?>" /></dd>

<dt>Link to download aircraft</dt>
<dd><input name="downloadlink" type="text"  value="<?php echo $aircraft->downloadlink; ?>" /></dd>

<dt>Link to aircraft image</dt>
<dd><input name="imagelink" type="text"  value="<?php echo $aircraft->imagelink; ?>" /></dd>

<dt>Range</dt>
<dd><input name="range" type="text"   value="<?php echo $aircraft->range; ?>" /></dd>

<dt>Weight</dt>
<dd><input name="weight" type="text" value="<?php echo $aircraft->weight; ?>" /></dd>

<dt>Cruise</dt>
<dd><input name="cruise" type="text"  value="<?php echo $aircraft->cruise; ?>" /></dd>

<dt></dt>
<dd><input type="hidden" name="id" value="<?php echo $aircraft->id;?>" />
	<input type="hidden" name="action" value="<?php echo $action;?>" />
	<input type="submit" name="submit" value="<?php echo $title;?>" />
</dd>
</dl>
</form>