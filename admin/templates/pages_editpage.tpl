<h3>Edit Page</h3>
<form action="<?php echo SITE_URL?>/admin/index.php/sitecms/viewpages" method="post">

<p><strong>Page name: </strong>
<?php
if(isset($action) && $action == 'addpage')
	echo '<input name="pagename" type="text">';
else
	echo $pagedata->pagename;
?>
</p>

<?php
if(isset($pagedata) && $pagedata->public == 1)
	$public = 'checked';
else
	$public = '';
	
if((isset($pagedata) && $pagedata->enabled == 1) || !isset($pagedata))
	$enabled =  'checked';
else
	$enabled = '';
?>
<p><strong>Page Content</strong></p>
<p><textarea name="content" id="editor" style="width: 90%; height:350px;"><?php if(isset($content)) echo $content;?></textarea></p>
<p><strong>Public?</strong> <input type="checkbox" name="public" value="true" <?php echo $public?> />  <strong>Enabled?</strong><input type="checkbox" name="enabled" value="true" <?php echo $enabled?> /></p>
<p> <input type="hidden" name="pageid" value="<?php echo $pagedata->pageid;?>" />
	<input type="hidden" name="action" value="<?php echo $action;?>" />
	<input type="submit" name="submit" value="Save Changes" /></p>
</form>
<script type="text/javascript">
//<![CDATA[
var editor = CKEDITOR.replace( 'editor',
{
height: '500px',
toolbar: [
    ['Source','-','Save','NewPage','Preview','-','Templates', 'Image'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    /*['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],*/
    ['Bold','Italic','Underline','Strike',/*'-','Subscript','Superscript'*/],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink','Anchor'],
    /*[,'Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],*/
    ['Styles','Format','Font','FontSize'],
    ['TextColor','BGColor'],
    ['Maximize', 'ShowBlocks','-','About']
  ]
});
editor.on( 'pluginsLoaded', function( ev )
{

});
//]]>
</script>
