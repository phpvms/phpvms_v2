$(document).ready(function() 
{
	$('#form').ajaxForm({
		target: '#scheduleresults',
		success: function() {
			$('#bodytext').fadeIn('slow');
		}
	});
	
	// The navigation, it'll apply superfish to it
	$(".nav").superfish({
		animation : { opacity:"show",height:"show"}
	});
	
	$.listen("change", "#code", function()
	{
		$("#depairport").load("action.php?page=getdeptapts&code="+$(this).val());
	});
	
	$.listen("change", "#depairport", function()
	{
		$("#arrairport").load("action.php?page=getarrapts&icao=" + $("#depicao").val() + "&code=" + $("#code").val());
	});
	
	$("#tabcontainer > ul").tabs();
});