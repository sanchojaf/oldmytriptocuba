// JavaScript Document
//Uniform js apply input,textbox,selectbox and button
$(function(){
	$("input, textarea, select, button").uniform();
});

//Jquery validataion engine
jQuery(document).ready(function(){
	// binds form submission and fields to the validation engine
	jQuery("#myForm").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) {           
				jQuery('.helpfade').show();
				jQuery('.helptips').show();
				var id = $('.ckeditor').attr('id');
				if(typeof id !='undefined'){
					var editorcontent = CKEDITOR.instances[id].getData().replace(/<[^>]*>/gi, '');
					if (editorcontent.length<=10){
						jQuery('.helpfade').hide();
						jQuery('.helptips').hide();
						message("This field is required, Please give minimum 10 characters in the field of "+id);
						return false;
					}
				}
				form.validationEngine('detach');
				form.submit();
			}
		}
	});
	
	jQuery("#myForms").validationEngine('attach', { 
		autoHidePrompt:true,
		autoHideDelay:3000,
		onValidationComplete: function(form, status){
			if (status == true) {           
				jQuery('.helpfade').show();
				jQuery('.helptips').show();
				var id = $('.ckeditor').attr('id');
				if(typeof id !='undefined'){
					var editorcontent = CKEDITOR.instances[id].getData().replace(/<[^>]*>/gi, '');
					if (editorcontent.length<=10){
						jQuery('.helpfade').hide();
						jQuery('.helptips').hide();
						message("This field is required, Please give minimum 10 characters in the field of "+id);
						return false;
					}
				}
				form.validationEngine('detach');
				form.submit();
			}
		}
	});


	$(".logintab").click(function () {
		$('.emailformError').remove();
		$('#email').val('');
		$('.forgotBox').slideUp('normal', function() {
			$('.loginBox').slideDown(function() {
			});
		});
	});
	
	$(".forgottab").click(function () {
		$('.usernameformError').remove();
		$('.passwordformError').remove();
		$('#username').val('');
		$('#password').val('');
		$('.loginBox').slideUp('normal', function() { 
			$('.forgotBox').slideDown(function() {
			});
		});
	});
	
	$("#msginfo").click(function () {	
		$("#msginfo").fadeOut(1000);
	});
	setTimeout(function(){  $('#msginfo').fadeOut(1000); }, 5000); 
	
});