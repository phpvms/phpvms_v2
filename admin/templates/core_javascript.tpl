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
	
	// Binding the AJAX call clicks
	$('.ajaxcall').bind('click', function() {		
		return false; // cancel the single click event
	});
	
	$('.ajaxcall').bind('dblclick', function() {
		$("#bodytext").load("action.php?admin="+$(this).attr("module"), {action: $(this).attr("action"), id: $(this).attr("id")});
	});
	
	// Make the message box hide itself
	//$("#messagebox")
	
	setTimeout(function() { $("#messagebox").slideUp("slow")}, 5000);
}
</script>