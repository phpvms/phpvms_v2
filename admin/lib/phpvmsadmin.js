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
 
function formInit()
{
    $("#form").ajaxForm({
		target: '#bodytext',
		success: function() 
		{
			$('#bodytext').fadeIn('slow');
			formInit();
		}
	});
}

function reloadGroups()
{
    // Binding the AJAX call clicks
	$.listen('click','.pilotgroupajax', function() {
		return false; // cancel the single click event
	});
	
	$.listen('dblclick','.pilotgroupajax', function() {
		$("#pilotgroups").load($(this).attr("href"), {action: $(this).attr("action"), pilotid: $(this).attr("pilotid"), groupid: $(this).attr("id")}, function() { reloadGroups(); });
	});
}

function dialogInit()
{
    $('#jqmdialog').jqm({
	    ajax:'@href',
		onLoad: function(h) 
		{
			 $("#form").ajaxForm({
				target: '#bodytext',
				success: function() 
				{
					$('#jqmdialog').jqmAddTrigger('.jqModal');
					$.listen('dblclick','.jqModal', function() { return false; });
					$('#bodytext').fadeIn('slow');
					$('#jqmdialog').jqmHide();
				}
			});

			h.w.show();
		},
	    onHide: function(h) {
            h.o.remove(); // remove overlay
            h.w.fadeOut(100); // hide window 
            $("#jqmdialog").html('');
            dialogInit();
        }
    });
}

$(document).ready(function() {
 		
    // Show dialog box
	
	dialogInit();
	formInit();
	reloadGroups();
	
	$("#slidermenu").accordion({ clearStyle: true, autoHeight: false, navigation: true });
	
	$(".tablesorter").tablesorter(); 
	
	$("#dialogform").ajaxForm({
		target: '#results'
	});
	
	//$.listen('click', '.confirm', function() { return false; });
	$.listen('click', '.confirm', function()
	{
		var url = $(this).attr("href");
		
		$.prompt('Are you sure?', {
			buttons: { Yes: true, Cancel: false },
			callback: function(v,m)
			{
				if(v == true)
				{
					$("#bodytext").load(url);
				}
			}			
		});
		
		return false;
	});
	
	$.listen('click', '.deleteitem', function(){return false;});
	$.listen('dblclick','.deleteitem', function(){	
		var url = $(this).attr("href");
		var action = $(this).attr("action");
		var id = $(this).attr("id");
		
		$.prompt('Are you sure?', {
			buttons: { Yes: true, Cancel: false },
			callback: function(v,m)
			{
				if(v == true)
				{
					$.post(url, {action: action, id: id});
					rmvid= "#row"+id;
					$(rmvid).slideUp();
				}
				
				$('#jqmdialog').jqmAddTrigger('.jqModal');
			}			
		});	
		
		return false;
	});
    
	$('#jqmdialog').jqmAddTrigger('.jqModal');
    $.listen('dblclick','.jqModal', function() { return false; });
		
	$('#pilotoptionchangepass').ajaxForm({
		target: '#dialogresult'
	});
	
	$('#selectpilotgroup').ajaxForm({
		target: '#pilotgroups' 
	});
	
	// Binding the AJAX call clicks
	$.listen('click','.ajaxcall', function() {
		return false; // cancel the single click event
	});
	
	$.listen('dblclick','.ajaxcall', function() {
		$("#bodytext").load($(this).attr("href"), {action: $(this).attr("action"), id: $(this).attr("id")});
	});
	
	// Binding the AJAX call clicks
	$.listen('click','.dialogajax', function() {
		return false; // cancel the single click event
	});
	
	$.listen('dblclick','.dialogajax', function() {
		
		$("#dialogresult").load($(this).attr("href"), {action: $(this).attr("action"), id: $(this).attr("id")});
	});
	
	//Tablize any lists
	$("#tabledlist").tablesorter();
	
	// Dynamically look up airport information based on the provided ICAO
	$.listen("click","#lookupicao", function()
	{
		icao = $("#airporticao").val();
		
		if(icao.length != 4)
		{
			$("#statusbox").html("Please enter the full 4 letter ICAO");
			return false;
		}
			
		$("#statusbox").html("Fetching airport data...");
		$("#lookupicao").hide();
		
		$.getJSON("http://ws.geonames.org/searchJSON?style=medium&maxRows=10&featureCode=AIRP&type=json&q="+icao+"&callback=?", 
			function(data){
			
			 //$("#airportname").autocomplete(data.geonames);
			 
			if(data.totalResultsCount == 0)
			{
				$("#statusbox").html("Nothing found. Try entering the full 4 letter ICAO");
				$("#lookupicao").show();
				return;
			}
		
			$.each(data.geonames, function(i,item){
				$("#airporticao").val(icao);
				$("#airportname").val(item.name);
				$("#airportcountry").val(item.countryName);
				$("#airportlat").val(item.lat);
				$("#airportlong").val(item.lng);
			
				$("#statusbox").html("");
				$("#lookupicao").show();
			});
		});
		
		return false;
	});
	
	if(document.getElementById('editor'))
	{
		new nicEditor({iconsPath : baseurl+'/lib/js/nicEditorIcons.gif', fullPanel:true}).panelInstance('editor');
	}
});