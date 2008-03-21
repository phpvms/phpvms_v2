$(document).ready(function() 
{
	$('#form').ajaxForm({
		target: '#scheduleresults',
		success: function() {
			$('#bodytext').fadeIn('slow');
		}
	});
	
	
	setTimeout(function() { $("#messagebox").slideUp("slow")}, 5000);
});