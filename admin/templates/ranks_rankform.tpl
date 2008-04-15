<h3><?=$title?></h3>
<form id="form" action="action.php?admin=pilotranks" method="post">
<dl>
	<dt>Rank Title</dt>
	<dd><input name="rank" type="text" value="<?=$rank->rank;?>" /></dd>
	
	<dt>Minimum Hours</dt>
	<dd><input name="minhours" type="text" value="<?=$rank->minhours;?>" /></dd>
	
	<dt></dt>
	<dd><input type="hidden" name="action" value="<?=$action;?>">
		<input type="submit" name="submit" value="<?=$title?>" /></dd> 
</dl>
</form>