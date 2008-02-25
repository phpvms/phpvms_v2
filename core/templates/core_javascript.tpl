<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery-1.2.2.pack.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.form.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/suckerfish.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/phpvms.js"></script>

<script type="text/javascript">
$(document).ready(function() 
{
	EvokeListeners();
});

function ProxyListen()
{
	EvokeListeners();
}

function EvokeListeners()
{
	$('#form').ajaxForm({
		target: '#bodytext',
    	success: function() {
         	$('#bodytext').fadeIn('slow');
         	ProxyListen();
    	}
    });
}
</script>