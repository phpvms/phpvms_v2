$(document).ready(function() 
{
	var url = window.location.href.split("index.php")[0];
	
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
		$("#depairport").load(url+"action.php/pireps/getdeptapts/"+$(this).val());
	});
		
	$("#tabcontainer > ul").tabs();
});