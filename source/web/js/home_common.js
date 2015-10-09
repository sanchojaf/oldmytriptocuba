$(document).ready(function(){
	
	$("#checkavail").leanModal({closeButton: ".modal_close"});
	
	/*******Responsive**********/			
	$(".flip").click(function(){
		$(".panel").slideToggle("slow");
	});
	
	$(".flips").click(function(){
		if ( $(this).parent('li').find('ul').is(':visible')){
		 $('.panels').slideUp();
		}else{  
		  $('.panels').slideUp();
		  $(this).parent('li').find('ul').slideToggle("slow");
		}		
	}); 
	
	
	$('#showDivId').leanModal({closeButton: ".mclose" });
	
	
	$("#contactform").validationEngine('attach', { 
		autoHidePrompt:false,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) {           
				$('.helpfade').show();
				$('.helptips').show();				
				form.validationEngine('detach');
				form.submit();
			}
		}
	});
	
	$("#signupform").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) {           
				jQuery('.helpfade').show();
				jQuery('.helptips').show();				
				form.validationEngine('detach');
				form.submit();
			}
		}
	});
	
	$("#beanstreamform").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) { 
				$('#cardnumber').validateCreditCard(function(e){ 
					if(e.length_valid==false){
						alert(error_msg.card_msg);
						return false;
					}else{
						jQuery('.helpfade').show();
						jQuery('.helptips').show();				
						form.validationEngine('detach');
						form.submit();
					}
				})
				return false;
				
			}
		}
	});
	
	$("#twitterform").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) { 
				jQuery('.helpfade').show();
				jQuery('.helptips').show();				
				form.validationEngine('detach');
				form.submit();
			}
		}
	});
	
	$("#loginform").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) {           
				jQuery('.helpfade').show();
				jQuery('.helptips').show();				
				form.validationEngine('detach');
				form.submit();
			}
		}
	});
	
	$("#forgotform").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){			
			if (status == true) {			        
				$('.helpfade').show();
				$('.helptips').show();								
				form.validationEngine('detach');				
				form.submit();
			}
		}
	});
	
	$("#profileform").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) {           
				jQuery('.helpfade').show();
				jQuery('.helptips').show();				
				form.validationEngine('detach');
				form.submit();
			}
		}
	});
	$("#homesearch").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) {           
				jQuery('.helpfade').show();
				jQuery('.helptips').show();				
				form.validationEngine('detach');
				form.submit();
			}
		}
	});
	
	$("#imageform").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) {           
				jQuery('.helpfade').show();
				jQuery('.helptips').show();				
				form.validationEngine('detach');
				form.submit();
			}
		}
	});
	
	$("#resetaccount").on('click',function() { 
		if($('#checkbox67').is(':checked') || $('#checkbox68').is(':checked') || $('#checkbox69').is(':checked') ) {        
			jQuery('.helpfade').show();
			jQuery('.helptips').show();	
			$('#resetform').submit();
		}else{		
			$('#alert_box').find('p').html(error_msg.rest_acc_msg);
			$("#showDivId").click();
			return false;
		}			
	});
	
	$('#cancelaccount').on('click',function(){
		
	});
	
	$("#changepasswordform").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) {           
				jQuery('.helpfade').show();
				jQuery('.helptips').show();				
				form.validationEngine('detach');
				form.submit();
			}
		}
	});
	
	$("#bookform").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:50000,
		onValidationComplete: function(form, status){
			if (status == true) {
				var checkin=$('#checkin').val();
				var checkout=$('#checkout').val();
				if(checkin!='' && checkout!=''){	 
					if($('#checkbox1').is(':checked')){
					var user=$('#userstatus').val(); 
					if(user==0){         
						jQuery('.helpfade').show();
						jQuery('.helptips').show();	
						$.ajax({
							type: "POST",
							url: "../../emailcheck",
							data: "fieldValue="+$('#bemail').val(),
							dataType:"json",
							success: function(msg){ 		
								if(msg[1]==false){
									$('#bemail').focus();
									$('#alert_box').find('p').html(error_msg.email_exit_msg);
									$("#showDivId").click();	
									jQuery('.helpfade').hide();
									jQuery('.helptips').hide();	
									return false;
								}else{
									$.ajax({
										type: "POST",
										url: "../../ajaxcheck",
										data: "des="+$('#bdestination').val()+"&hostal="+$('#bhostal').val()+"&checkin="+$('#checkin').val()+"&checkout="+$('#checkout').val()+"&rooms="+$('#rooms').val(),
										dataType:'json',
										success: function(msg){
											if(msg.avl==0){
												$('#checkin').focus(); 		
												$('#alert_box').find('p').html(msg.msg);
												$("#showDivId").click();
												jQuery('.helpfade').hide();
												jQuery('.helptips').hide();	
												return false;
											}else{
												form.validationEngine('detach');
												form.submit();
											}
										}
									});
									return false;							
								}
							}
						});	
						return false
					 }else{
						$.ajax({
							type: "POST",
							url: "../../ajaxcheck",
							data: "des="+$('#bdestination').val()+"&hostal="+$('#bhostal').val()+"&checkin="+$('#checkin').val()+"&checkout="+$('#checkout').val()+"&rooms="+$('#rooms').val(),
							dataType:'json',
							success: function(msg){
								if(msg.avl==0){ 
									$('#checkin').focus(); 			
									$('#alert_box').find('p').html(msg.msg);
									$("#showDivId").click();
									jQuery('.helpfade').hide();
									jQuery('.helptips').hide();	
									return false;
								}else{
									form.validationEngine('detach');
									form.submit();
								}
							}
						});
						return false; 
					 }
					}else{
						$('#alert_box').find('p').html(error_msg.term_msg);
						$("#showDivId").click();
						jQuery('.helpfade').hide();
						jQuery('.helptips').hide();
						return false; 
					}
				}else{
					$('#alert_box').find('p').html(error_msg.avail_msg);
					$("#showDivId").click();
					jQuery('.helpfade').hide();
					jQuery('.helptips').hide();
					return false; 
				}
			}
		}
	});
	
	
	
	$('#country').change(function(){
		var id=$(this).val();
		$.ajax({
			type: "POST",
			url: "getstate",
			data: "sid="+id,
			success: function(msg){ 		
				$('#province').html(msg);									
			}
		});
	});
	
	$('#bcountry').change(function(){
		var id=$(this).val();
		$.ajax({
			type: "POST",
			url: "../../getstate",
			data: "sid="+id,
			success: function(msg){ 		
				$('#bprovince').html(msg);									
			}
		});
	});
	
	$('#bcheckavailability').click(function(){
		var des=$('#bdestination').val();
		var hostal=$('#bhostal').val();
		var checkin=$('#checkin').val();
		var checkout=$('#checkout').val();
		var rooms=$('#rooms').val();
		if(checkin!='' && checkout!=''){
		$.ajax({
			type: "POST",
			url: "../../ajaxcheck",
			data: "des="+des+"&hostal="+hostal+"&checkin="+checkin+"&checkout="+checkout+"&rooms="+rooms,
			dataType:'json',
			success: function(msg){ 		
				$('#alert_box').find('p').html(msg.msg);
			 	$("#showDivId").click();								
			}
		});
		}else{			
			$('#alert_box').find('p').html(error_msg.date_msg);
			$("#showDivId").click();
		}
	});
	
	$('#paymentsubmit').submit(function(){
		if(!$('#partial').is(':checked') && !$('#full').is(':checked')){
			$('#alert_box').find('p').html(error_msg.payment_msg);
			$("#showDivId").click();
			return false;
		}else if(!$('#paypal').is(':checked') && !$('#beanstream').is(':checked') && !$('#globalone').is(':checked')){
			$('#alert_box').find('p').html(error_msg.payment_mode_msg);
			$("#showDivId").click();
			return false;
		}else{
			jQuery('.helpfade').show();
			jQuery('.helptips').hide();
			return true;
		}
	});
	
		$('.stars [type*="radio"]').change(function () {
			var me = $(this);
			$('#ratings').val(me.attr('value'));
		});
		
	$('.reviewtext').maxlength( {maxCharacters: 300,slider: true} ); 
	
	$('#reviewform').submit(function(){		
		if(!$('#star-1').is(':checked') && !$('#star-2').is(':checked') && !$('#star-3').is(':checked') && !$('#star-4').is(':checked') && !$('#star-5').is(':checked')){
			$('#alert_box').find('p').html(error_msg.rating_msg);
			$("#showDivId").click();
			return false;
		}else if(($('#hostalreview').val()).length < 3 ){
			$('#alert_box').find('p').html(error_msg.rating_write_msg);
			$("#showDivId").click();
			$('#hostalreview').focus();
			return false;
		}else{	
			$('.helpfade').show();
			$('.helptips').show();
			if($('#reviewhostal').val()!=''){
				urls='../review';
			}else{
				urls='review';
			}
			$.ajax({
				type: "POST",
				url: urls,
				data: "rate="+$('#ratings').val()+"&review="+$('#hostalreview').val()+"&destination="+$('#reviewdestination').val()+"&hostal="+$('#reviewhostal').val()+"&type="+$('#reviewtype').val(),
				dataType:'json',
				success: function(msg){ 
					$('.helpfade').hide();
					$('.helptips').hide();	
					if(msg.suc=="2"){
						$("#toplogin").click();
						 $("html, body").animate({ scrollTop: 0 }, 600); 	
						//$('#alert_box').find('p').html(msg.msg);
						//$("#showDivId").click();
					}else{
						$('#alert_box').find('p').html(msg.msg);
						$("#showDivId").click();
						if(msg.suc=="1"){
							$('#ratings').val('');
							$('#hostalreview').val('');
							$('.stars [type*="radio"]').removeAttr("checked");
						}
					}
					
				}
				});
			return false;
		}
	});
	
	$('.cancelbook').click(function(){
		var result = $.confirm({
			'title'		: 'Confirmation',
			'message'	: error_msg.cancel_booking_msg,
			'buttons'	: {
				'Yes'	: {
					'class'	: 'blue',
					'action': function(){
						$('#cancelbooking').submit();
					}
				},
				'No'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
		if(!result)		
		return false;
	});
	
	$('#cancelaccount').click(function(){
		var result = $.confirm({
			'title'		: 'Confirmation',
			'message'	: error_msg.del_acc_msg,
			'buttons'	: {
				'Yes'	: {
					'class'	: 'blue',
					'action': function(){
						$('#canform').submit();
					}
				},
				'No'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
		if(!result)		
		return false;
	});
	
	
});

$(function() {	
	$( "#hdes" ).autocomplete({
		source: "homesearchdestination",
		minLength: 2,		
	});
});

    