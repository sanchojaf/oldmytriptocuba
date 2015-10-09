$(document).ready(function(){
	url=window.location.href
	url=url.replace(window.location.search,'');

	if($(window).width()>1210){		
		$('#show-next-month').calendar({
			num_next_month: 1,
			num_prev_month: 1,
			day_first : 1,		
			adapter: url+'/roomavailablity',		
      });	 
	}else if($(window).width()<=1210 && $(window).width()>=800){
		$('#show-next-month').calendar({
			num_next_month: 1,       
			day_first : 1,		
			adapter: url+'/roomavailablity',		
      });	 
	}else if( $(window).width()< 800 ){
		$('#show-next-month').calendar({			      
			day_first : 1,		
			adapter: url+'/roomavailablity',		
      });
	}
	 
	  $('.cal_prev').click(function(e){		
		  $('.datetimepicker:first').find('.prev').click();
		   setTimeout(function() {	  
	  $('.datetimepicker').find('tbody').find('td').filter('.available,.toconfirm,.unavailable,.topast').append('<span class="dateprice">'+$('#hprice').val()+'</span>');	  
	 },2000);
		  e.stopPropagation();
	  });
	   $('.cal_next').click(function(){
		  $('.datetimepicker:first').find('.next').click();
		   setTimeout(function() {	  
	  $('.datetimepicker').find('tbody').find('td').filter('.available,.toconfirm,.unavailable,.topast').append('<span class="dateprice">'+$('#hprice').val()+'</span>');	  
	 },2000);
	  });
	  
	 setTimeout(function() {	  
	  $('.datetimepicker').find('tbody').find('td').filter('.available,.toconfirm,.unavailable,.topast').append('<span class="dateprice">'+$('#hprice').val()+'</span>');	  
	 },2000);
	 
	 
	
});
$(window).resize(function(){		
	if (!$('#availabilitycalender').is(':hidden')) {			
		if(window.location.search=='?cal=1'){
			window.location.reload();
		}else{
			window.location=window.location.href+'?cal=1'
		}
	}else{
		window.location.reload();
	}
});
//window.location.href

    