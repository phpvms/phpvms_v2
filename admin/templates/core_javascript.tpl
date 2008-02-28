<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery-1.2.2.pack.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.form.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/suckerfish.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/phpvms.js"></script>

<script type="text/javascript">
$(document).ready(function() { EvokeListeners(); });

function EvokeListeners()
{
	// The navigation, it'll apply superfish to it
	$(".nav").superfish({
		animation : { opacity:"show",height:"show"}
	});
	
	// Dynamic submit of the whole form
	$('#form').ajaxForm({
		target: '#bodytext',
		success: function() {
			$('#bodytext').fadeIn('slow');
		}
	});
	
	// Options for binding the dynamic clicks
	$('.ajaxcall').bind('click', function() {
		// cancel the single click event
		return;
	});
	
	$('.ajaxcall').bind('dblclick', function() {
		// do stuff
		$("#bodytext").load("action.php", $(this).attr("params"));
	});
	
	// Make the message box hide itself
	//$("#messagebox")
}
</script>