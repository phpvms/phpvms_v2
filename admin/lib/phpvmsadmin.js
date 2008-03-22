$(document).ready(function() { EvokeListeners(); });

function EvokeListeners()
{
	// Dynamic submit of the whole form
	$('#form').ajaxForm({
		target: '#bodytext',
		success: function() {
			$('#bodytext').fadeIn('slow');
            $('#jqmdialog').jqmHide();
		}
	});
	
	//Tabs
	$("#tabcontainer > ul").tabs();
	
    // Show dialog box
	$('#jqmdialog').jqm({
	    ajax:'@href',
	    onHide: function(h) {
            h.o.remove(); // remove overlay
            h.w.fadeOut(1500); // hide window 
            $("$jqmdialog").html('');
        }
    });
    
	$('#jqmdialog').jqmAddTrigger('.jqModal');
	 
	// Show editor
	$("#editor").wysiwyg();
	
	$('#pilotoptionchangepass').ajaxForm({
		target: '#dialogresult'
	});
	
	$('#selectpilotgroup').ajaxForm({
		target: '#pilotgroups' 
	});
	
	// Binding the AJAX call clicks
	$('.ajaxcall').bind('click', function() {		 
		return false; // cancel the single click event
	});
	
	$('.ajaxcall').bind('dblclick', function() {
		$("#bodytext").load($(this).attr("href"), {action: $(this).attr("action"), id: $(this).attr("id")});
	});
	
	// Binding the AJAX call clicks
	$('.dialogajax').bind('click', function() {		 
		return false; // cancel the single click event
	});
	
	$('.dialogajax').bind('dblclick', function() {
		$("#dialogresult").load($(this).attr("href"), {action: $(this).attr("action"), id: $(this).attr("id")});
	});
	
	// Binding the AJAX call clicks
	$('.pilotgroupajax').bind('click', function() {		 
		return false; // cancel the single click event
	});
	
	$('.pilotgroupajax').bind('dblclick', function() {
		$("#pilotgroups").load($(this).attr("href"), {action: $(this).attr("action"), pilotid: $(this).attr("pilotid"), groupid: $(this).attr("id")});
	});
	
	// Make the message box hide itself
	setTimeout(function() { $("#messagebox").slideUp("slow")}, 5000);
	
	//Tablize any lists
	$("#tabledlist").tablesorter();
	
	// Dynamically look up airport information based on the provided ICAO
	$("#lookupicao").bind('click', function()
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
}