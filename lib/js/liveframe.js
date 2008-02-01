
$(document).ready(function() 
{ 
	var open=function(hash){ hash.w.fadeIn(); };
	var close=function(hash) { hash.w.slideUp('',function(){ 
		hash.o.remove(); 
		$("#dialog").empty();
	}); };
	
	$("#dialog").jqm({ajax:'@href', overlay:65, modal: true, trigger:'a.popup', onShow: open, onHide: close});
});

function SaveForm(form_id)
{
	sendReq(form_id, '#results', 'action.php');	
	return false;
}

function sendReq(form_id, div, url)
{
	$(div).val("Loading...");
	$(div).innerHTML = '';
	var options = { 
		target:     div, 
		url:        url, 
		type: "post"
	};
	
	$(form_id).ajaxSubmit(options);
}