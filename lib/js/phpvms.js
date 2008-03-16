$(document).ready(function() 
{
	$('#form').ajaxForm({
		target: '#scheduleresults',
		success: function() {
			$('#bodytext').fadeIn('slow');
		}
	});
});