<h3><?=$title?></h3>
<form id="form" action="action.php?admin=pilotranks" method="post">
<dl>
	<dt>Rank Title</dt>
	<dd><input name="rank" type="text" value="<?=$rank->rank;?>" /></dd>
	
	<dt>Minimum Hours</dt>
	<dd><input name="minhours" type="text" value="<?=$rank->minhours;?>" /></dd>
	
	<dt>Pay Rate</dt>
	<dd><input name="payrate" type="text" value="<?=$rank->payrate;?>" />
		<p>Enter the hourly pay rate for this rank</p>
	</dd>
	
	<dt>Image URL</dt>
	<dd><input name="rankimage" type="text" value="<?=$rank->rankimage;?>" />
		<p>Enter the full URL, or path from root to the image</p>
	</dd>
	
	<dt></dt>
	<dd><input type="hidden" name="rankid" value="<?=$rank->rankid;?>" />
		<input type="hidden" name="action" value="<?=$action;?>">
		<input type="submit" name="submit" value="<?=$title;?>" /></dd> 
</dl>
</form>