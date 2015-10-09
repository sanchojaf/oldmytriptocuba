$(document).ready(function(){
	$("#toplogin").leanModal({closeButton: ".modal_close"});
	$("#topsignup").leanModal({closeButton: ".modal_close"});
	
	$("#topforgot").leanModal({closeButton: ".modal_close"});
		
	$('#already_login,#already_logins').click(function(){		
		 $("html, body").animate({ scrollTop: 0 }, 600);
		 $('#signup').find(".modal_close").click();  		
		 $('#toplogin').click();
		 setTimeout(function () {  $('#lean_overlay').css({'display':'block'}); }, 200);		
	});
	
	$('#forgotbutton').click(function(){		
		 $('#signup,#login').find(".modal_close").click();
		 $("html, body").animate({ scrollTop: 0 }, 600); 		
		 setTimeout(function () {  $('#topforgot').click(); setTimeout(function () {  $('#lean_overlay').css({'display':'block'}); }, 200); }, 200);		
	});
	
	$('#signupbutton').click(function(){		
		 $('#forgotdiv,#login').find(".modal_close").click();
		 $("html, body").animate({ scrollTop: 0 }, 600); 		
		 setTimeout(function () {  $('#topsignup').click(); setTimeout(function () {  $('#lean_overlay').css({'display':'block'}); }, 200); }, 200);		
	});
	
	$('#footersignup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		$('#topsignup').click();
	});
	
	$('#footerlogin').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		$('#toplogin').click();
	});
	
	$('#topsignup').click(function(){
		$('#signupform').find('input[type=text]').val('');
		$('#signupform').find('input[type=password]').val('');
	});
	$('#toplogin').click(function(){
		$('#loginform').find('input[type=text]').val('');
		$('#loginform').find('input[type=password]').val('');
	});
	
});