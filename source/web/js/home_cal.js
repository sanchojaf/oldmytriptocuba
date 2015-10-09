$(document).ready(function(){
	/***Homepage check availability**********/
	$('#checkin').Zebra_DatePicker({
		direction: true,
		pair: $('#checkout')
	});
	
	$('#checkin').on('focus',function(){
		$('.checkinformError').hide();
		$('#checkout').val('');
	});
	
	$('#checkout').Zebra_DatePicker({
		direction: 1
	});
	
});