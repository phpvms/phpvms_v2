$(document).ready(function() 
{
	var url = window.location.href.split("index.php")[0];
	
	$('#form').ajaxForm({
		target: '#scheduleresults',
		success: function() {
			$('#bodytext').fadeIn('slow');
		}
	});
		
	$("#code").live("change", function()
	{
		$("#depairport").load(url+"action.php/pireps/getdeptapts/"+$(this).val());
	});
		
	$("#tabcontainer > ul").tabs();
	
	$('.deleteitem').live('click',function(){return false;});
	$('.deleteitem').live('dblclick', function(){
		$.post($(this).attr("href"), {id: $(this).attr("id")});
		rmvid= "#bid"+$(this).attr("id"); $(rmvid).slideUp();
		return false;
	});
	
	$('.addbid').live('click', function(){	
		$.post($(this).attr("href"), {id: $(this).attr("id")});
		id = "#"+$(this).attr("id");
		$(id).html("Added");
		
		return false;
	});
	
	$("div .metar").each(function(){
		icao=$(this).attr("id");
		$.getJSON("http://ws.geonames.org/weatherIcaoJSON?ICAO="+icao+"&callback=?", 
		function(data){
		 	if(data.length == 0) {
		 		html = "Could not load METAR information";
		 	}
		 	else {
				data.weatherObservation.observation = data.weatherObservation.observation.replace("$", "");
				html = "<strong>METAR: </strong>"+data.weatherObservation.observation+"<br />";
			}
			
			$("#"+data.weatherObservation.ICAO).html(html);
		});
	});
});