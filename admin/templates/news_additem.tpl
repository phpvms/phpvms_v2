<h3><?=$title?></h3>

<form action="index.php?admin=viewnews" method="post">
<p><strong>Subject: </strong><input type="text" name="subject" value="<?=$newsitem->subject?>" /></p>
	<p>
	<strong>News Text: </strong><br />
		<textarea id="editor" name="body" style="width: 550px; height: 250px;"><?=$newsitem->body?></textarea>
		</p>
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="id" value="<?php echo $newsitem->id; ?>" />
	<input type="submit" name="submit" value="<?=$title?>" />
	</form>