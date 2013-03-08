
$(".delete_playdate").click(function() {
	var n = $('.stageevent_playdate').size();
	if(n > 1) {
	$('.stageevent_playdate:last').remove();
	}
		
});
		
$(".add_playdate").click(function() {
	
	$(".stageevent_playdate:last").clone().appendTo($("#playtimetable"));
		
});