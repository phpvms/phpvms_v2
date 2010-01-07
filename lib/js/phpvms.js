/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
 
var url = window.location.href.split("index.php")[0];

// Global icons for maps
var depIcon = new GIcon(G_DEFAULT_ICON);
depIcon.image = url + '/lib/images/towerdeparture.png';
depIcon.iconSize = new GSize(35,35);

var arrIcon= new GIcon(G_DEFAULT_ICON);
arrIcon.image = url + '/lib/images/towerarrival.png';
arrIcon.iconSize = new GSize(35,35);

// Everything else
$(document).ready(function() 
{
	$('#form, .ajaxform').ajaxForm({
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
		var id = "#"+$(this).attr("id");
		
		$.post(url+"action.php/schedules/addbid", {id: $(this).attr("id")}, 
			function (data, status)
			{
				$(id).html(data);
			});
			
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