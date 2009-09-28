
var url = window.location.href.split("index.php")[0];

$(document).ready(function() 
{
	$('#form').ajaxForm({
		target: '#scheduleresults',
		beforeSubmit: function (x,y,z) {
		    $("#scheduleresults").html('<div align="center"><img src="'+url+'/lib/images/loading.gif" /><br />Searching...</div>');
		},
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
		$.getJSON(geourl+"/weatherIcaoJSON?ICAO="+icao+"&callback=?", 
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

/**
 * attachInfoWindow() binds InfoWindow to a Marker 
 * Creates InfoWindow instance if it does not exist already 
 * @extends Marker
 * @param InfoWindow options
 * @author Esa 2009
 */
google.maps.Marker.prototype.attachInfoWindow = function (options){
	var map_ = this.getMap();
	var ac_data = this.acData;
	map_.bubble_ = map_.bubble_ || new google.maps.InfoWindow();
	google.maps.event.addListener(this, 'click', function () {
		map_.bubble_.setOptions(options);
		map_.bubble_.open(map_, this);
		
		// Chart flight path, clicked on marker
		$.ajax({
			type: "GET",
			url: url+"/action.php/acars/routeinfo?depicao="+this.acData.depicao+"&arricao="+this.acData.arricao,
			success: function (data) { draw_route(data, ac_data); },
			error: function (x, s, t) { /*console.log(s); console.log(t);*/ },
			dataType: "json"
		});
	});
	
	map_.infoWindowClickShutter = map_.infoWindowClickShutter || 
	google.maps.event.addListener(map_, 'click', function () {
		clear_selected_route();
		map_.bubble_.close();
	});
}

/**
 * accessInfoWindow()
 * @extends Map
 * @returns {InfoWindow} reference to the InfoWindow object instance
 * Creates InfoWindow instance if it does not exist already 
 * @author Esa 2009
 */
google.maps.Map.prototype.accessInfoWindow = function (){
  this.bubble_ = this.bubble_ || new google.maps.InfoWindow();
  return this.bubble_;
}