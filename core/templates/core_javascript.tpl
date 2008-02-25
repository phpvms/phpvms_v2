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