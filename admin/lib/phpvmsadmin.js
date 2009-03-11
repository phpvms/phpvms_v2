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
	
		
	$('#pilotoptionchangepass').ajaxForm({
		target: '#dialogresult',
		success: function() 
		{
			formInit();
		}
	});
	
	$('#selectpilotgroup').ajaxForm({
		target: '#pilotgroups',
		success: function() 
		{
			formInit();
		}
	});
	
	$('#addaward').ajaxForm({
		target: '#awardslist',
		success: function() 
		{
			formInit();
		}
	});
}

function reloadGroups()
{
    // Binding the AJAX call clicks
	$('.pilotgroupajax').live('click', function() {
		return false; // cancel the single click event
	});
	
	$('.pilotgroupajax').live('dblclick', function() {
		$("#pilotgroups").load($(this).attr("href"), 
		    { action: $(this).attr("action"), pilotid: $(this).attr("pilotid"), groupid: $(this).attr("id")}, 
		    function() 
            { reloadGroups(); 
            });
	});
}

function calcDistance()
{
     $("#distance").val("Calculating...");
    $.get(baseurl+"/admin/action.php/operations/calculatedistance", {depicao: $("#depicao").val(), arricao: $("#arricao").val()},
    function (data){
        $("#distance").val(data);
    });
}


$(document).ready(function() 
{ 		
      // Show dialog box
	
	  $('#jqmdialog').jqm({
	    ajax:'@href',
		onLoad: function(h) 
		{
			 $("#form").ajaxForm({
				target: '#bodytext',
				success: function() 
				{
					$('#jqmdialog').jqmAddTrigger('.jqModal');
					//$.listen('dblclick','.jqModal', function() { return false; });
		
					$('#bodytext').fadeIn('slow');
					$('#jqmdialog').jqmHide();
				}
			});

			h.w.show();
		},
	    onHide: function(h) {
            h.o.remove(); // remove overlay
            h.w.fadeOut(100); // hide window 
            $("#jqmdialog").html("");
            //dialogInit();
        }
    });
    
	formInit();
	reloadGroups();
	
	$("#slidermenu").accordion({ clearStyle: true, autoHeight: false, navigation: true });
	
	$(".tablesorter").tablesorter(); 
	
	$("#dialogform").ajaxForm({
		target: '#results'
	});
	
	$('.confirm').live('click', function() { return false; });
	$('.confirm').live('click', function()
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
	
	$('.deleteitem').live('click', function(){ return false; });
	$('.deleteitem').live('dblclick', function(){	
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
    $('.jqModal').live('dblclick', function() { return false; });
		
	// Binding the AJAX call clicks
	$('.ajaxcall').live('click', function() {
		return false; // cancel the single click event
	});
	
	$('.ajaxcall').live('dblclick', function() {
		$("#bodytext").load($(this).attr("href"), {action: $(this).attr("action"), id: $(this).attr("id")});
	});
	
	$('.awardajaxcall').live('click', function() {
		return false; // cancel the single click event
	});
	
	$('.awardajaxcall').live('dblclick', function() {
		$("#awardslist").load($(this).attr("href"), {action: $(this).attr("action"), id: $(this).attr("id")});
	});
	
	// Binding the AJAX call clicks
	$('.dialogajax').live('click', function() {
		return false; // cancel the single click event
	});
	
	$('.dialogajax').live('dblclick', function() {
		$("#dialogresult").load($(this).attr("href"), {action: $(this).attr("action"), id: $(this).attr("id")});
	});
	
	//Tablize any lists
	$("#tabledlist").tablesorter();
		
	if(document.getElementById('editor'))
	{
		new nicEditor({iconsPath : baseurl+'/lib/js/nicEditorIcons.gif', fullPanel:true}).panelInstance('editor');
	}
});

function lookupICAO()
{
	icao = $("#airporticao").val();
	
	if(icao.length != 4)
	{
		$("#statusbox").html("Please enter the full 4 letter ICAO");
		return false;
	}
		
	$("#statusbox").html("Fetching airport data...");
	$("#lookupicao").hide();
	
	$.getJSON(geourl+"/searchJSON?style=medium&maxRows=10&featureCode=AIRP&type=json&q="+icao+"&callback=?", 
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
}