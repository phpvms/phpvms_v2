<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/ui.tabs.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/ui.tabs.ext.pack.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.form.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jqModal.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.wysiwyg.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.tablesorter.pack.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.metadata.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/phpvms.js"></script>

<link rel="stylesheet" href="<?=SITE_URL?>/lib/js/ui.tabs.css" type="text/css" />
<link rel="stylesheet" href="<?=SITE_URL?>/lib/js/jqModal.css" type="text/css" />
<link rel="stylesheet" href="<?=SITE_URL?>/lib/js/jquery.wysiwyg.css" type="text/css" />
<link rel="stylesheet" href="<?=SITE_URL?>/lib/css/phpvms.css" type="text/css" />

<script type="text/javascript">
$(document).ready(function() { EvokeListeners(); });

function EvokeListeners()
{
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
	setTimeout(function() { $("#messagebox").slideUp("slow")}, 5000);
	
	//Tablize any lists
	$("#tabledlist").tablesorter();
	
	//Tabs
	 $("#tabcontainer > ul").tabs();
	
	// Show dialog box
	 $('#dialog').jqm({ajax:'@href'});
	 
	// Show editor
	$("#newseditor").wysiwyg();
}
</script>