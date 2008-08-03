$(document).ready(function() 
{
	var url = window.location.href.split("index.php")[0];
	
	$('#form').ajaxForm({
		target: '#scheduleresults',
		success: function() {
			$('#bodytext').fadeIn('slow');
		}
	});
		
	$.listen("change", "#code", function()
	{
		$("#depairport").load(url+"action.php/pireps/getdeptapts/"+$(this).val());
	});
		
	$("#tabcontainer > ul").tabs();
	
	$.listen('click', '.deleteitem', function(){return false;});
	$.listen('dblclick','.deleteitem', function(){
		$.post($(this).attr("href"), {id: $(this).attr("id")});
		rmvid= "#bid"+$(this).attr("id"); $(rmvid).slideUp();
		return false;
	});
	
	$.listen('click','.addbid', function(){	
		$.post($(this).attr("href"), {id: $(this).attr("id")});
		id = "#"+$(this).attr("id");
		$(id).html("Added");
		
		return false;
	});
});