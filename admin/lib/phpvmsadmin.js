$(document).ready(function() {
 	
	$.listen('dblclick','#jqmdialog', function()
	{
		return false;
	});
	
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
        }
    });
	
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
					//$("#bodytext").load(url);
					alert ('ok');
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
	
	$("ul.nav").superfish();
	
	// Binding the AJAX call clicks
	$.listen('click','.pilotgroupajax', function() {
		return false; // cancel the single click event
	});
	
	$.listen('dblclick','.pilotgroupajax', function() {
		$("#pilotgroups").load($(this).attr("href"), {action: $(this).attr("action"), pilotid: $(this).attr("pilotid"), groupid: $(this).attr("id")});
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
});