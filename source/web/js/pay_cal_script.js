$(document).ready(function(){
	url=window.location.href
	url=url.replace(window.location.search,'');

	if($(window).width()>1210){		
		$('#show-next-month').calendar({
			num_next_month: 1,
			num_prev_month: 1,
			day_first : 1,		
			adapter: url+'/roomavailablity',
			onSelectDate: function(date, month, year){
			    $('td', this.$element).filter('.available').filter(function(){
					var data = $(this).data();					
					if(data!=''){
						if(data.date == date && data.month == month && data.year == year){
							 var data=year+"-"+(month<10?'0'+month:month)+"-"+(date<10?'0'+date:date);
							 $('#checkin').val(data);
							 document.getElementById('checkin').scrollIntoView();
							 $('#availabilitycalender').find('.modal_close').click();
						}
					}
				});
			}
			/*onSelectDate: function(date, month, year){
				alert($(this).attr('class'));
				 var data=year+"-"+month+"-"+date;
				 $('#checkin').val(data);
				 $('#availabilitycalender').find('.modal_close').click();
       		 }	*/			
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
	 
	 $('.cur-month').click(function(){
		 alert($(this).parents('td').attr('class'));
		/* onSelectDate: function(date, month, year){
				 var data=year+"-"+month+"-"+date;
				 $('#checkin').val(data);
				 $('#availabilitycalender').find('.modal_close').click();
       		 }*/
	 });
	
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

    