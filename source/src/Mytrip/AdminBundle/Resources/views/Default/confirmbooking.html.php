<?php $view->extend('::admin.html.php');
$paging=$pagination->getPaginationData();
$em = $this->container->get('doctrine')->getManager();
?>
<div id="content" class="clearfix"> 
	<div class="container">
    <div class="mainheading">   
    <div class="btnlink"></div> 		
        <div class="titletag"><h1>Confirm Booking</h1></div>
    </div>
  <div class="tablefooter clearfix">
 <form name="searchfilters" action="" id="myForm1" method="post" style="width:1020px;float:left;padding: 5px 10px;">  
        <table cellpadding="0" cellspacing="2">
        <tr><td><strong>Ref No : </strong>&nbsp;</td>
        <td><input id="searchref" name="searchref" type="text" class="validate[groupRequired[payments]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['ref'])){echo $_REQUEST['ref'];}?>" /></td><td>&nbsp;</td>
        <td><strong>Email : </strong>&nbsp;</td>
        <td><input id="searchemail" name="searchemail" type="text" class="validate[groupRequired[payments]] text-input" autocomplete="off" value="<?php if(isset($_REQUEST['email'])){echo $_REQUEST['email'];}?>" /></td><td>&nbsp;</td>
        <td><strong>&nbsp;From&nbsp;Date&nbsp;:&nbsp;</strong>&nbsp;</td>
        <td><input id="cdate" name="cdate" size="25"  type="text"value="<?php if(isset($_REQUEST['cdate'])){echo $_REQUEST['cdate'];}?>" /></td>
        <td><strong>&nbsp;To&nbsp;Date&nbsp;:&nbsp;</strong>&nbsp;</td>
        <td><input id="edate" name="edate" size="25"  type="text"value="<?php if(isset($_REQUEST['edate'])){echo $_REQUEST['edate'];}?>" /></td>  <td>&nbsp;</td>   
        <td><input type="submit" name="searchbutton" class="button small" value="Search" /></td>
            <td>&nbsp;</td><td>
		<?php if(isset($_REQUEST['search'])){	
		?>
        <a href="<?php echo $view['router']->generate('mytrip_admin_confirm_booking');?>" class="button small" style="padding:3px 5px;">Cancel</a>
        <?php
		} ?></td>
        </tr></table></form>        
  </div>
<form action="" id="myForm" method="post">
    <table class="gtable sortable">
        <thead>
        <tr>
            <th width="30" align="center"><img src="<?php echo $view['assets']->getUrl('img/icons/arrow.jpg');?>"/></th>
            <th width="30" align="center">#</th>
            <th align="left" class="name">Ref.No</th>
            <th align="left" class="name">Name</th> 
            <th align="center" class="destination">Destination</th> 
            <th align="center" class="hostal">Hostal</th>             
            <th align="center" class="paymenttype">Payment Type</th> 
            <th align="left" class="amount">Amount</th> 
            <th align="center">View</th>
        </tr>
        </thead>
        <tbody>  
		<?php if(!$pagination->getItems())
		echo '<tr><td colspan="9" align="center">No records found</td></tr>';
	else{
		$i=$paging['firstItemNumber'];				
		foreach($pagination as $booking){
			$bookings=$booking[0];			
			$user=$em->createQuery("SELECT u FROM MytripAdminBundle:User u WHERE u.userId='".$booking['users']."'")->getArrayResult();			
			$hostal=$em->createQuery("SELECT h,IDENTITY(h.destination) AS destination FROM MytripAdminBundle:Hostal h WHERE h.hostalId='".$booking['hostals']."'")->getArrayResult();
			$destination=$em->createQuery("SELECT d FROM MytripAdminBundle:Destination d WHERE d.destinationId='".$hostal[0]['destination']."'")->getArrayResult();
			$booking_price=$em->createQuery("SELECT d FROM MytripAdminBundle:BookingPrice d WHERE d.booking='".$bookings->getBookingId()."'")->getArrayResult();
			
			?>
      <tr >
       <td align="center"><img src="<?php echo $view['assets']->getUrl('img/icons/arrow.jpg');?>"/></td>
       <td align="center"><?php echo $i; ?></td>
       <td align="left"><?php echo 'venacuba-'.$bookings->getBookingId()*1024;?></td>    
       <td align="left"><?php echo $user[0]['firstname']." ".$user[0]['lastname']; ?></td>        
       <td align="center"><?php echo $destination[0]['name']; ?></td>   
       <td align="center"><?php echo $hostal[0][0]['name']; ?></td>
       <td align="center"><?php echo ucfirst($booking_price[0]['paymenttype']);?></td>
       <td align="left"><?php echo number_format($booking_price[0]['reservationTotalPrice']*$booking_price[0]['conversionRate'],2);?> <?php echo $booking_price[0]['conversionCurrency'];?></td>
       <td align="center"><a href="<?php echo $view['router']->generate('mytrip_admin_viewbooking',array('id'=>$bookings->getBookingId(),'type'=>'confirm'));?>"><img src="<?php echo $view['assets']->getUrl('img/icons/view.png');?>"/></a></td>
      </tr>
      <?php $i++;
	  }
	  }?>
        </tbody>
    </table>
  <div class="tablefooter clearfix">   
   
    
    <div class="pagination">
    <div class="pagenumber">
    Page <?php echo $paging['current'];?> of <?php echo $paging['last'];?>, showing <?php echo $paging['currentItemCount'];?> records out of <?php echo $paging['totalCount'];?>	
    </div>
        <div class="paging">
         <span class="prev <?php echo ($paging['current']=="1"?"disabled":"");?>"><?php if($paging['current']>1){ echo '<a href="?'.$urlrequest.'&page='.($paging['current']-1).'">Previous</a>'; }else{ echo "Previous"; }?></span>
         <?php for($i=1;$i<=$paging['last'];$i++){ ?>
         <span <?php echo ($paging['current']==$i?'class="current"':""); ?> ><?php echo ($paging['current']!=$i ? '<a href="?'.$urlrequest.'&page='.$i.'">'.$i.'</a>':$i);?></span>
		 <?php }?>
         <span class="next <?php echo ($paging['current']==$paging['last']?"disabled":"");?>"><?php if($paging['current']< $paging['last']){ echo '<a href="?'.$urlrequest.'&page='.($paging['current']+1).'">Next</a>'; }else{ echo "Next"; }?></span> 
        </div>
    </div>
  </div> 
</form>
  </div>
</div>

<script type="text/javascript">
$(function() {
	$( "#cdate" ).datepicker({ dateFormat: '<?php echo $this->container->get('mytrip_admin.helper.date')->dateformat();?>' });
	$( "#edate" ).datepicker({ dateFormat: '<?php echo $this->container->get('mytrip_admin.helper.date')->dateformat();?>' });
});
</script>