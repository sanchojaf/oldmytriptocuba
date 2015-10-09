<?php $view->extend('::admin.html.php');
$em = $this->container->get('doctrine')->getManager();
$bookingprice=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingPrice p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$bookingtransaction=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingTransaction p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$bookinginfo=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingInfo p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
$bookinginfo_province=$em->createQuery("SELECT p FROM MytripAdminBundle:States p WHERE p.sid='".$bookinginfo[0]['province']."'")->getArrayResult();
$bookinginfo_country=$em->createQuery("SELECT p FROM MytripAdminBundle:Country p WHERE p.cid='".$bookinginfo[0]['country']."'")->getArrayResult();
$hostal=$em->createQuery("SELECT p FROM MytripAdminBundle:Hostal p WHERE p.hostalId='".$booking[0]['hostal']."'")->getArrayResult();
?>
<div id="content"  class="clearfix">
  <div class="container">
    <div align="right" style="padding: 10px 10px 0px;"><a href="<?php if(isset($_REQUEST['type'])){ echo ($_REQUEST['type']=="confirm"?$view['router']->generate('mytrip_admin_confirm_booking'):$view['router']->generate('mytrip_admin_cancel_booking')); }?>" class="button">Back to <?php if(isset($_REQUEST['type'])){ echo ($_REQUEST['type']=="confirm"?"Confirm":"Cancel"); }?> Bookings</a></div>
    <form action="<?php echo $view['router']->generate('mytrip_admin_viewusers',array('id'=>$_REQUEST['id'])) ?>" id="myForm" method="post">
    <fieldset>
      <legend>Booking Details</legend>
      <dl class="inline">
        <dt><label>Reference No</label></dt>
        <dd><p><?php echo 'venacuba-'.$booking[0][0]['bookingId']*1024;?></p></dd>
        <dt><label>Hostal</label></dt>
        <dd><p><?php echo $hostal[0]['name'];?></p></dd>
        <dt><label>Check In</label></dt>
        <dd><p><?php echo date('l dS M Y',strtotime($booking[0][0]['fromDate']->format('Y-m-d H:i:s')));?></p></dd>
        <dt><label>Check Out</label></dt>
        <dd><p><?php echo date('l dS M Y',strtotime($booking[0][0]['toDate']->format('Y-m-d H:i:s')));?></p></dd>
        <dt><label>No. of Days</label></dt>
        <dd><p><?php echo $booking[0][0]['noOfDays'];?> nights</p></dd> 
        <dt><label>No. of Rooms</label></dt>
        <dd><p><?php echo $booking[0][0]['noOfRooms'];?> rooms</p></dd> 
        <dt><label>Guests</label></dt>
        <dd><p><?php echo $booking[0][0]['adults'];?> Adults / <?php echo $booking[0][0]['child'];?> Child Per Room</p></dd> 
        <dt><label>Price per Room</label></dt>
        <dd><p><?php echo number_format($bookingprice[0]['roomPrice']*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></p></dd> 
        <dt><label>Accommodation total cost</label></dt>
        <dd><p><?php echo number_format($bookingprice[0]['totalPrice']*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></p></dd> 
        <dt><label>Reservation fee</label></dt>
        <dd><p><?php echo number_format($bookingprice[0]['reservationCharge']*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></p></dd> 
        <dt><label>Total Price</label></dt>
        <dd><p><?php echo number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></p></dd>
      </dl>
    </fieldset>
    <fieldset>
      <legend>Booking Info</legend>
      <dl class="inline">
        <dt><label>First Name</label></dt>
        <dd><p><?php echo $bookinginfo[0]['firstname'];?></p></dd>
        <dt><label>Last Name</label></dt>
        <dd><p><?php echo $bookinginfo[0]['lastname'];?></p></dd>
        <dt><label>Email</label></dt>
        <dd><p><?php echo $bookinginfo[0]['email'];?></p></dd>
        <dt><label>Gender</label></dt>
        <dd><p><?php echo $bookinginfo[0]['gender'];?></p></dd>
        <dt><label>Phone</label></dt>
        <dd><p><?php echo $bookinginfo[0]['phone'];?></p></dd> 
        <dt><label>Mobile</label></dt>
        <dd><p><?php echo $bookinginfo[0]['mobile'];?></p></dd> 
        <dt><label>Address</label></dt>
        <dd><p><?php echo $bookinginfo[0]['address'];?></p></dd> 
        <dt><label>Address1</label></dt>
        <dd><p><?php echo $bookinginfo[0]['address1']==''?'-':$bookinginfo[0]['address1'];?></p></dd> 
        <dt><label>City</label></dt>
        <dd><p><?php echo $bookinginfo[0]['city'];?></p></dd> 
        <dt><label>Province</label></dt>
        <dd><p><?php echo $bookinginfo_province[0]['state'];?></p></dd> 
        <dt><label>Country</label></dt>
        <dd><p><?php echo $bookinginfo_country[0]['country'];?></p></dd>
        <dt><label>Zip</label></dt>
        <dd><p><?php echo $bookinginfo[0]['zip'];?></p></dd>
      </dl>
    </fieldset>
    <fieldset>
      <legend>Payments</legend>
      <dl class="inline">
        <dt><label>Total Price</label></dt>
        <dd><p><?php echo number_format(($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></p></dd>
        <?php
		  if($bookingprice[0]['paymenttype']=="partial"){
		?>
        <dt><label>Paid Amount</label></dt>
        <dd><p><?php echo number_format($bookingprice[0]['reservationTotalPrice']*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></p></dd>
        <dt><label>Balance Amount</label></dt>
        <dd><p><?php echo number_format((($bookingprice[0]['totalPrice']+$bookingprice[0]['reservationCharge'])-$bookingprice[0]['reservationTotalPrice'])*$bookingprice[0]['conversionRate'],2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></p></dd>        
         <?php }else{?>   
        <dt><label>Paid Amount</label></dt>
        <dd><p><?php echo number_format(($bookingprice[0]['reservationTotalPrice'] * $bookingprice[0]['conversionRate']),2);?> <?php echo $bookingprice[0]['conversionCurrency'];?></p></dd>
         <?php }?> 
      </dl>
    </fieldset>
    <fieldset>
      <legend>Payments Details</legend>
      <dl class="inline">
        <dt><label>Payment Mode</label></dt>
        <dd><p><?php echo $bookingtransaction[0]['paymentType'];?></p></dd>
        <dt><label>Transaction Id</label></dt>
        <dd><p><?php echo $bookingtransaction[0]['transactionId'];?></p></dd>
        <dt><label>Transaction Date</label></dt>
        <dd><p><?php echo $this->container->get('mytrip_admin.helper.date')->viewformat($bookingtransaction[0]['transactionDate']);?></p></dd>
        <dt><label>Transaction Amount</label></dt>
        <dd><p><?php echo $bookingtransaction[0]['transactionAmount'];?> <?php echo $bookingtransaction[0]['transactionCurrency'];?></p></dd>
      </dl>
    </fieldset>
    <?php 
	if($booking[0][0]['status']=="Cancelled"){ 
		  $bookingcancel=$em->createQuery("SELECT p FROM MytripAdminBundle:BookingCancel p WHERE p.booking='".$booking[0][0]['bookingId']."'")->getArrayResult();
		  ?>
    <fieldset>
      <legend>Cancellation Details</legend>
      <dl class="inline">
        <dt><label>Transaction Id</label></dt>
        <dd><p><?php echo $bookingcancel[0]['refundReferenceno'];?></p></dd>
        <dt><label>Refund Date</label></dt>
        <dd><p><?php $return_date=$bookingcancel[0]['refundDate']; echo $this->container->get('mytrip_admin.helper.date')->viewformat($return_date->format('Y-m-d'));?></p></dd>
        <dt><label>Refund Amount</label></dt>
        <dd><p><?php echo $bookingcancel[0]['refundAmount'];?> <?php echo $bookingcancel[0]['refundCurrency'];?></p></dd>
      </dl>
    </fieldset>
     <?php }?>
    </form>
     </div>
</div>

