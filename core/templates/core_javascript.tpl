<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=GOOGLE_KEY?>" type="text/javascript"></script>

<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.form.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/suckerfish.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/phpvms.js"></script>

<?=$MODULE_HEAD_INC;?>

<script type="text/javascript">

$(document).ready(function(){ EvokeListeners(); });

function EvokeListeners()
{
	// The navigation, it'll apply superfish to it
	$(".nav").superfish({
		animation : { opacity:"show",height:"show"}
	});
	
	$("#code").bind('change', function()
	{
		$("#depairport").load("action.php?page=getdeptapts&code="+$(this).val());
	});
	
	$("#depairport").bind('change', function()
	{
		$("#arrairport").load("action.php?page=getarrapts&icao=" + $("#depicao").val() + "&code=" + $("#code").val());
	});
}
</script>
