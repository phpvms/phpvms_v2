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

var depicon = baseurl + '/lib/images/towerdeparture.png';
var arricon = baseurl + '/lib/images/towerarrival.png';

function formInit() 
{
	$("#form").ajaxForm({
		target: '#bodytext',
		success: function() {
			$('#bodytext').fadeIn('slow');
			formInit();
			$("button, input:button, input:submit, a.button").button();
		}
	});

	$("#flashForm").ajaxForm({
		target: '#results',
		success: function() {
			formInit();
			$("button, input:button, input:submit, a.button").button();
		}
	});

	$('#pilotoptionchangepass').ajaxForm({
		target: '#dialogresult',
		success: function() {
			formInit();
		}
	});

	$('#selectpilotgroup').ajaxForm({
		target: '#pilotgroups',
		success: function() {
			formInit();
		}
	});

	$('#addaward').ajaxForm({
		target: '#awardslist',
		success: function() {
			formInit();
		}
	});
	
	$("button, input:button, input:submit, a.button").button();
}

function reloadGroups() {
	$('.pilotgroupajax').live('click', function() {
		$("#pilotgroups").load($(this).attr("href"),
		    { action: $(this).attr("action"), pilotid: $(this).attr("pilotid"), groupid: $(this).attr("id") },
		    function() {
		    	reloadGroups();
		    });
	});

	$('a.button').button();
	$('button').button();
}

function calcDistance() {
	$("#distance").val("Calculating...");
	$.get(baseurl + "/admin/action.php/operations/calculatedistance",
        { depicao: $("#depicao").val(), arricao: $("#arricao").val() },
        function(data) {
        	$("#distance").val(data);
        });
}

function initListeners()
{
	$('#jqmdialog').jqm({
		ajax: '@href',
		onLoad: function(h) {
			$("#form").ajaxForm({
				target: '#bodytext',
				success: function() {
					$('#jqmdialog').jqmAddTrigger('.jqModal');
					$('#bodytext').fadeIn('slow');
					$('#jqmdialog').jqmHide();
					$('a.button, button, input[type=submit]').button();
				}
			});

			$("#flashForm").ajaxForm({
				target: '#results',
				success: function() {
					$('#jqmdialog').jqmAddTrigger('.jqModal');
					$('#jqmdialog').jqmHide();
					
				}
			});
			
			h.w.show();
			$('a.button, button, input[type=submit]').button();
		},
		onHide: function(h) {
			h.o.remove(); // remove overlay
			h.w.fadeOut(100); // hide window 
			$("#jqmdialog").html("");
		}
	});

	$('#jqmdialog').jqmAddTrigger('.jqModal');
	$('.jqModal').live('dblclick', function() { return false; });

	$('a.button, button, input[type=submit]').button();
	
	formInit();
	reloadGroups();

	$("#slidermenu").accordion({ clearStyle: true, autoHeight: false, navigation: true });

	$("#dialogform").ajaxForm({
		target: '#results'
	});

	$('.confirm').live('click', function() {
		var url = $(this).attr("href");

		var answer = confirm("Are you sure you want to delete?")
		if (answer) {
			$("#bodytext").load(url);
			$('a.button, button, input[type=submit]').button();
		}

		return false;
	});

	$('.deleteitem').live('click', function() {
		var href = $(this).attr("href");
		var action = $(this).attr("action");
		var id = $(this).attr("id");
		var rmvid = "#row" + id;
	
		var answer = confirm("Are you sure you want to delete?")
		if (answer) {
			$.post(href, { action: action, id: id }, function(d)
			{
				$(rmvid).fadeOut();
				$('a.button, button, input[type=submit]').button();
			});
		}

		return false;
	});

	$('.ajaxcall').live('click', function() {
		$("#bodytext").load($(this).attr("href"), { action: $(this).attr("action"), id: $(this).attr("id") },
		function(d) { $('a.button, button, input[type=submit]').button(); })
	});
	
	$('.ajaxcallconfirm').live('click', function() {
		var answer = confirm("Are you sure you want to delete?")
		if (answer) {
			$("#bodytext").load($(this).attr("href"), { action: $(this).attr("action"), id: $(this).attr("id") },
			function(d) { $('a.button, button, input[type=submit]').button(); })
		}
	});
	
	// Call for PIREPs
	$('.pirepaction').live('click', function() {
		$("#pireplist").load($(this).attr("href"), { action: $(this).attr("action"), id: $(this).attr("id") },
		function(d) { $('a.button, button, input[type=submit]').button(); })
	});

	$('.awardajaxcall').live('click', function() {
		$("#awardslist").load($(this).attr("href"), { action: $(this).attr("action"), id: $(this).attr("id"), pilotid: $(this).attr("pilotid") },
		function(d) { $('a.button, button, input[type=submit]').button(); })
	});

	// Binding the AJAX call clicks

	$('.dialogajax').live('click', function() {
		$("#dialogresult").load($(this).attr("href"), { action: $(this).attr("action"), id: $(this).attr("id") },
		function(d) { $('a.button, button, input[type=submit]').button(); })
	});

	// Binding the AJAX call clicks
	$('.deletecomment').live('click', function() {
		return false; // cancel the single click event
	});

	$('.deletecomment').live('dblclick', function() {
		$("#dialogresult").load($(this).attr("href"), { action: $(this).attr("action"), id: $(this).attr("id") });
		$("#row" + $(this).attr("id")).hide();
	});

	if (document.getElementById('editor')) {
		var editor = CKEDITOR.replace('editor',
	    {
	    	height: '500px',
	    	toolbar: [
		    ['Source', '-', 'Save', 'NewPage', 'Preview', '-', 'Templates', 'Image'],
		    ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Print', 'SpellChecker', 'Scayt'],
		    ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'],
	    	/*['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],*/
		    ['Bold', 'Italic', 'Underline', 'Strike', /*'-','Subscript','Superscript'*/],
		    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote'],
		    ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
		    ['Link', 'Unlink', 'Anchor'],
	    	/*[,'Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],*/
		    ['Styles', 'Format', 'Font', 'FontSize'],
		    ['TextColor', 'BGColor'],
		    ['Maximize', 'ShowBlocks', '-', 'About']
	      ]
	    });
		editor.on('pluginsLoaded', function(ev) {

		});
	}
}

$(document).ready(function() {
	initListeners();
});

function lookupICAO() {
	icao = $("#airporticao").val();

	if (icao.length != 4) {
		$("#statusbox").html("Please enter the full 4 letter ICAO");
		return false;
	}

	$("#statusbox").html("Fetching airport data...");
	$("#lookupicao").hide();

	if (airport_lookup == "geonames") {
		url = geourl + "/searchJSON?style=medium&maxRows=10&featureCode=AIRP&type=json&q=" + icao + "&callback=?";
	}
	else {
		url = phpvms_api_server + "/airport/get/" + icao + "&callback=?";
	}

	$.getJSON(url,
		function(data) {

			if (data.totalResultsCount == 0) {
				$("#statusbox").html("Nothing found. Try entering the full 4 letter ICAO");
				$("#lookupicao").show();
				return;
			}

			if (data.geonames) {
				data.airports = data.geonames;
			}

			$.each(data.airports, function(i, item) {
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

/**
* Equal Heights Plugin
* Equalize the heights of elements. Great for columns or any elements
* that need to be the same size (floats, etc).
* 
* Version 1.0
* Updated 12/10/2008
*
* Copyright (c) 2008 Rob Glazebrook (cssnewbie.com) 
*
* Usage: $(object).equalHeights([minHeight], [maxHeight]);
* 
* Example 1: $(".cols").equalHeights(); Sets all columns to the same height.
* Example 2: $(".cols").equalHeights(400); Sets all cols to at least 400px tall.
* Example 3: $(".cols").equalHeights(100,300); Cols are at least 100 but no more
* than 300 pixels tall. Elements with too much content will gain a scrollbar.
* 
*/

(function($) {
	$.fn.equalHeights = function(minHeight, maxHeight) {
		tallest = (minHeight) ? minHeight : 0;
		this.each(function() {
			if ($(this).height() > tallest) {
				tallest = $(this).height();
			}
		});
		if ((maxHeight) && tallest > maxHeight) tallest = maxHeight;
		return this.each(function() {
			$(this).height(tallest).css("overflow", "auto");
		});
	}
})(jQuery);